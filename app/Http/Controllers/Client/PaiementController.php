<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    /**
     * Page de checkout
     */
    public function checkout($commandeId)
    {
        $user = Auth::user();
        
        $commande = Commande::where('user_id', $user->id)
            ->with(['lignes.oeuvre', 'devise'])
            ->findOrFail($commandeId);

        if ($commande->statut === 'payee' || $commande->statut === 'livree') {
            return redirect()->route('client.commandes.show', $commande->id)
                ->with('error', 'Cette commande est déjà payée.');
        }

        $paiementExistant = Paiement::where('commande_id', $commande->id)
            ->where('statut', 'en_attente')
            ->first();

        if (!$paiementExistant) {
            $paiementExistant = Paiement::create([
                'commande_id' => $commande->id,
                'user_id' => $user->id,
                'devise_id' => $commande->devise_id ?? 1,
                'reference' => 'PAY-' . strtoupper(Str::random(10)),
                'montant' => $commande->total_ttc,
                'statut' => 'en_attente',
                'methode' => 'shwary',
                'client_nom' => $user->name,
                'client_email' => $user->email,
                'client_telephone' => $user->phone ?? $user->telephone ?? null,
            ]);
        }

        $paiement = $paiementExistant;

        return view('client.paiement.checkout', compact('commande', 'paiement'));
    }

    /**
     * Initier un paiement avec SHWARY
     */
    public function initiate(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'mode_paiement' => 'required|in:orange_money,airtel_money,mpesa',
            'phone' => 'required|string|min:9|max:10',
        ]);

        $commande = Commande::where('user_id', $user->id)
            ->with(['devise', 'lignes.oeuvre'])
            ->findOrFail($request->commande_id);

        if ($commande->statut === 'payee' || $commande->statut === 'livree') {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande est déjà payée.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $phone = preg_replace('/[^0-9]/', '', $request->phone);
            $fullPhone = '+243' . $phone;

            $paiement = Paiement::where('commande_id', $commande->id)
                ->where('statut', 'en_attente')
                ->first();

            if (!$paiement) {
                $reference = 'PAY-' . strtoupper(Str::random(10));
                $paiement = Paiement::create([
                    'commande_id' => $commande->id,
                    'user_id' => $user->id,
                    'devise_id' => $commande->devise_id ?? 1,
                    'reference' => $reference,
                    'montant' => $commande->total_ttc,
                    'statut' => 'en_attente',
                    'methode' => $request->mode_paiement,
                    'client_nom' => $user->name,
                    'client_email' => $user->email,
                    'client_telephone' => $phone,
                    'payload' => [
                        'phone' => $phone,
                        'full_phone' => $fullPhone,
                        'commande_reference' => $commande->reference,
                    ],
                ]);
            } else {
                $paiement->update([
                    'methode' => $request->mode_paiement,
                    'client_telephone' => $phone,
                    'payload' => array_merge($paiement->payload ?? [], [
                        'phone' => $phone,
                        'full_phone' => $fullPhone,
                        'commande_reference' => $commande->reference,
                    ]),
                ]);
            }

            // ============================================
            // 📱 APPEL API SHWARY
            // ============================================
            $merchantId = config('services.shwary.merchant_id');
            $merchantIkey = config('services.shwary.merchant_ikey');
            $isSandbox = config('services.shwary.sandbox', true);
            
            $baseUrl = config('services.shwary.api_url', 'https://sandbox.shwary.com/api/v1');

            $paymentData = [
                'merchant_id' => $merchantId,
                'merchant_ikey' => $merchantIkey,
                'amount' => (float) $commande->total_ttc,
                'currency' => $commande->devise->code ?? 'FC',
                'reference' => $paiement->reference,
                'description' => 'Paiement commande ' . $commande->reference,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $fullPhone,
                'mode' => $request->mode_paiement,
                'callback_url' => config('services.shwary.callback_url'),
                'redirect_url' => route('client.paiement.confirmation', $commande->id),
                'send_sms' => true,
                'send_push' => true,
            ];

            Log::info('SHWARY Payment Initiation', [
                'data' => $paymentData,
                'url' => $baseUrl . '/payments/initiate'
            ]);

            // ============================================
            // APPEL API AVEC CURL
            // ============================================
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/payments/initiate');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-API-Key: ' . $merchantIkey,
                'Content-Type: application/json',
                'Accept: application/json',
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            Log::info('SHWARY Response', [
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error
            ]);

            // ============================================
            // TRAITEMENT DE LA RÉPONSE
            // ============================================
            if ($error) {
                DB::rollBack();
                Log::error('SHWARY cURL Error', ['error' => $error]);
                
                return response()->json([
                    'success' => false,
                    'message' => '❌ Erreur de connexion au service de paiement: ' . $error,
                ], 500);
            }

            $data = json_decode($response, true);

            if ($httpCode >= 200 && $httpCode < 300 && isset($data['status']) && $data['status'] === 'success') {
                // ✅ SUCCÈS - Notification envoyée sur le téléphone
                $paiement->update([
                    'transaction_id' => $data['transaction_id'] ?? null,
                    'shwary_transaction_id' => $data['transaction_id'] ?? null,
                    'payload' => array_merge($paiement->payload ?? [], $data),
                    'sms_envoye' => true,
                    'sms_envoye_at' => now(),
                ]);

                // Mettre à jour la commande (en attente de confirmation)
                $commande->update([
                    'statut' => 'en_cours',
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => '📱 Demande de paiement envoyée sur votre téléphone ' . $fullPhone . '. Veuillez confirmer sur votre mobile.',
                    'reference' => $paiement->reference,
                    'transaction_id' => $data['transaction_id'] ?? null,
                    'phone' => $fullPhone,
                    'redirect_to' => route('client.paiement.confirmation', $commande->id),
                ]);

            } else {
                DB::rollBack();
                
                $errorMessage = $data['message'] ?? $response ?? 'Erreur inconnue';
                Log::error('SHWARY Payment Error', [
                    'status' => $httpCode,
                    'body' => $response,
                    'data' => $data
                ]);

                return response()->json([
                    'success' => false,
                    'message' => '❌ Erreur lors du paiement: ' . $errorMessage,
                ], 500);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => '❌ Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Callback SHWARY
     */
    public function callback(Request $request)
    {
        Log::info('SHWARY Callback Received', $request->all());

        try {
            $data = $request->all();
            
            $paiement = Paiement::where('reference', $data['reference'] ?? '')
                ->orWhere('transaction_id', $data['transaction_id'] ?? '')
                ->orWhere('shwary_transaction_id', $data['transaction_id'] ?? '')
                ->first();

            if (!$paiement) {
                Log::warning('Payment not found for callback', $data);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            $statut = $data['status'] ?? 'echoue';
            $nouveauStatut = $statut === 'success' || $statut === 'paye' ? 'paye' : 'echoue';
            
            $paiement->update([
                'statut' => $nouveauStatut,
                'callback_data' => $data,
                'date_paiement' => $nouveauStatut === 'paye' ? now() : null,
                'date_validation' => $nouveauStatut === 'paye' ? now() : null,
            ]);

            if ($nouveauStatut === 'paye') {
                DB::beginTransaction();
                try {
                    $commande = $paiement->commande;
                    $commande->update([
                        'statut' => 'en_cours',
                        'date_livraison' => now()->addDays(7),
                    ]);

                    DB::commit();

                    Log::info('✅ Payment confirmed for order', [
                        'commande_id' => $commande->id,
                        'reference' => $commande->reference,
                        'paiement_id' => $paiement->id,
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment confirmed',
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Callback payment error', [
                        'message' => $e->getMessage(),
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Database error'], 500);
                }
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error('Callback error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }

    /**
     * Page de confirmation
     */
    public function confirmation($commandeId)
    {
        $user = Auth::user();
        
        $commande = Commande::where('user_id', $user->id)
            ->with(['devise', 'lignes.oeuvre', 'paiements'])
            ->findOrFail($commandeId);

        $paiement = $commande->paiements()->latest()->first();

        return view('client.paiement.confirmation', compact('commande', 'paiement'));
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function status($paiementId)
    {
        $user = Auth::user();
        
        $paiement = Paiement::where('user_id', $user->id)
            ->with('commande')
            ->findOrFail($paiementId);

        return response()->json([
            'success' => true,
            'statut' => $paiement->statut,
            'commande_id' => $paiement->commande_id,
            'reference' => $paiement->reference,
            'montant' => $paiement->montant,
            'methode' => $paiement->methode,
            'phone' => $paiement->client_telephone,
        ]);
    }
}