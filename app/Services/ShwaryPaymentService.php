<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShwaryPaymentService
{
    protected $merchantIkey;
    protected $merchantId;
    protected $callbackUrl;
    protected $sandbox;
    protected $timeout;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantIkey = config('shwary.merchant_ikey');
        $this->merchantId = config('shwary.merchant_id');
        $this->callbackUrl = config('shwary.callback_url');
        $this->sandbox = config('shwary.sandbox', true);
        $this->timeout = config('shwary.timeout', 30);
        
        $this->baseUrl = $this->sandbox 
            ? config('shwary.endpoints.sandbox')
            : config('shwary.endpoints.production');
    }

    /**
     * Créer un paiement Shwary
     */
    public function createPayment(array $data)
    {
        try {
            $payload = [
                'merchant_ikey' => $this->merchantIkey,
                'merchant_id' => $this->merchantId,
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'CDF',
                'reference' => $data['reference'],
                'customer_name' => $data['customer_name'] ?? '',
                'customer_email' => $data['customer_email'] ?? '',
                'customer_phone' => $data['customer_phone'] ?? '',
                'callback_url' => $this->callbackUrl,
                'description' => $data['description'] ?? 'Achat sur ArtMarket',
                'language' => 'fr',
                'return_url' => $data['return_url'] ?? route('client.paiement.confirmation'),
            ];

            Log::info('SHWARY: Création paiement', ['payload' => $payload]);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/payments/create', $payload);

            Log::info('SHWARY: Réponse création paiement', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error' => true,
                'message' => $response->body() ?? 'Erreur lors de la création du paiement',
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('SHWARY: Erreur création paiement', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus(string $transactionId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($this->baseUrl . '/payments/status', [
                    'merchant_ikey' => $this->merchantIkey,
                    'transaction_id' => $transactionId,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error' => true,
                'message' => $response->body() ?? 'Erreur lors de la vérification'
            ];

        } catch (\Exception $e) {
            Log::error('SHWARY: Erreur vérification paiement', [
                'message' => $e->getMessage()
            ]);

            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyCallback(array $data)
    {
        if (isset($data['signature'])) {
            return true;
        }
        return true;
    }
}