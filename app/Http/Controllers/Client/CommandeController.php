<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Oeuvre;
use App\Models\Paiement;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Commande::where('user_id', $user->id)
            ->with(['devise', 'lignes.oeuvre', 'paiements']);

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('search') && $request->search) {
            $query->where('reference', 'LIKE', "%{$request->search}%");
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'total' => Commande::where('user_id', $user->id)->count(),
            'en_attente' => Commande::where('user_id', $user->id)->where('statut', 'en_attente')->count(),
            'en_cours' => Commande::where('user_id', $user->id)->where('statut', 'en_cours')->count(),
            'livree' => Commande::where('user_id', $user->id)->where('statut', 'livree')->count(),
            'annulee' => Commande::where('user_id', $user->id)->where('statut', 'annulee')->count(),
            'total_depenses' => Commande::where('user_id', $user->id)->where('statut', 'livree')->sum('total_ttc') ?? 0,
        ];

        $statuts = ['en_attente', 'en_cours', 'expediee', 'livree', 'annulee', 'rembourse'];

        return view('client.commandes.index', compact('commandes', 'stats', 'statuts'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $commande = Commande::where('user_id', $user->id)
            ->with(['user', 'devise', 'lignes.oeuvre.images', 'lignes.oeuvre.artisan', 'paiements'])
            ->findOrFail($id);

        // ✅ Vérifier si le client peut payer
        $peutPayer = $commande->statut === 'en_attente' && !$commande->paiements()->where('statut', 'paye')->exists();

        return view('client.commandes.show', compact('commande', 'peutPayer'));
    }

    /**
     * ✅ CRÉER UNE COMMANDE ET REDIRIGER VERS LE PAIEMENT
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $panier = session()->get('panier', []);

        if (empty($panier)) {
            return redirect()->route('client.panier.index')
                ->with('error', 'Votre panier est vide.');
        }

        try {
            DB::beginTransaction();

            $totalHT = 0;
            $lignes = [];
            $premierOeuvre = null;

            foreach ($panier as $item) {
                $oeuvre = Oeuvre::with('devise')->find($item['id']);
                
                if (!$oeuvre || $oeuvre->stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour l'œuvre '{$oeuvre->titre}'");
                }

                if (!$premierOeuvre) {
                    $premierOeuvre = $oeuvre;
                }

                $prix = $oeuvre->prix_promo ?? $oeuvre->prix;
                $totalHT += $prix * $item['quantite'];

                $lignes[] = [
                    'oeuvre_id' => $oeuvre->id,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $prix,
                    'total' => $prix * $item['quantite'],
                ];
            }

            // ✅ Pas de frais de livraison (paiement en ligne)
            $fraisLivraison = 0;
            $commission = $totalHT * 0.05; // 5% de commission
            $totalTTC = $totalHT + $fraisLivraison + $commission;

            $reference = 'CMD-' . strtoupper(Str::random(8));

            $commande = Commande::create([
                'user_id' => $user->id,
                'devise_id' => $premierOeuvre->devise_id ?? 1,
                'reference' => $reference,
                'total_ht' => $totalHT,
                'total_ttc' => $totalTTC,
                'frais_livraison' => $fraisLivraison,
                'commission' => $commission,
                'statut' => 'en_attente',
                'adresse_livraison' => $request->adresse_livraison ?? $user->adresse ?? 'Adresse non renseignée',
                'ville_livraison' => $request->ville_livraison ?? $user->ville ?? '',
                'code_postal_livraison' => $request->code_postal_livraison ?? $user->code_postal ?? '',
                'pays_livraison' => $request->pays_livraison ?? $user->pays ?? 'France',
                'notes' => $request->notes,
            ]);

            foreach ($lignes as $ligne) {
                $commande->lignes()->create($ligne);
                
                $oeuvre = Oeuvre::find($ligne['oeuvre_id']);
                $stockAvant = $oeuvre->stock;
                $oeuvre->decrement('stock', $ligne['quantite']);

                Stock::create([
                    'oeuvre_id' => $oeuvre->id,
                    'type' => 'sortie',
                    'quantite' => $ligne['quantite'],
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $oeuvre->stock,
                    'motif' => 'Commande #' . $reference,
                    'commande_id' => $commande->id,
                    'user_id' => $user->id,
                ]);
            }

            session()->forget('panier');

            DB::commit();

            // ✅ REDIRIGER VERS LE PAIEMENT
            return redirect()->route('client.paiement.checkout', $commande->id)
                ->with('success', 'Commande créée ! Veuillez procéder au paiement.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('client.panier.index')
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function confirmation($id)
    {
        $user = Auth::user();
        $commande = Commande::where('user_id', $user->id)
            ->with(['devise', 'lignes.oeuvre'])
            ->findOrFail($id);

        return view('client.commandes.confirmation', compact('commande'));
    }

    public function annuler($id)
    {
        $user = Auth::user();

        $commande = Commande::where('user_id', $user->id)
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            foreach ($commande->lignes as $ligne) {
                $oeuvre = $ligne->oeuvre;
                $stockAvant = $oeuvre->stock;
                $oeuvre->increment('stock', $ligne->quantite);

                Stock::create([
                    'oeuvre_id' => $oeuvre->id,
                    'type' => 'entree',
                    'quantite' => $ligne->quantite,
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $oeuvre->stock,
                    'motif' => 'Annulation commande #' . $commande->reference,
                    'commande_id' => $commande->id,
                    'user_id' => $user->id,
                ]);
            }

            $commande->update(['statut' => 'annulee']);

            DB::commit();

            return redirect()->route('client.commandes.index')
                ->with('success', 'Commande annulée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function export(Request $request)
    {
        $user = Auth::user();

        $query = Commande::where('user_id', $user->id);

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        $commandes = $query->get();

        $filename = 'commandes_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($commandes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Référence', 'Total', 'Statut', 'Date']);

            foreach ($commandes as $commande) {
                fputcsv($file, [
                    $commande->reference,
                    number_format($commande->total_ttc, 2) . ' ' . ($commande->devise->symbole ?? 'FC'),
                    $commande->statut,
                    $commande->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 📄 TÉLÉCHARGER LE REÇU DE PAIEMENT EN PDF
     */
    public function telechargerRecu($id)
    {
        $user = Auth::user();
        
        $commande = Commande::where('user_id', $user->id)
            ->with(['user', 'devise', 'lignes.oeuvre.images', 'lignes.oeuvre.artisan', 'paiements'])
            ->findOrFail($id);

        // Vérifier que la commande est payée
        $paiement = $commande->paiements()->where('statut', 'paye')->first();
        
        if (!$paiement) {
            return back()->with('error', 'Cette commande n\'a pas encore été payée.');
        }

        // Générer le PDF
        $pdf = Pdf::loadView('client.commandes.recu', compact('commande', 'paiement'));
        $pdf->setPaper('A4', 'portrait');
        
        // Télécharger le PDF
        return $pdf->download('recu-commande-' . $commande->reference . '.pdf');
    }
}