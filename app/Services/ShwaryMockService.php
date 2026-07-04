<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ShwaryMockService
{
    public function initiatePayment($data)
    {
        Log::info('SHWARY Mock - Payment Initiated', $data);
        
        return [
            'success' => true,
            'message' => 'Paiement initié (MOCK)',
            'transaction_id' => 'MOCK-' . strtoupper(uniqid()),
            'payment_url' => route('client.paiement.confirmation', $data['commande_id'] ?? 1),
            'redirect_url' => route('client.paiement.confirmation', $data['commande_id'] ?? 1),
        ];
    }
}