<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'commande_id',
        'user_id',
        'devise_id',
        'reference',
        'transaction_id',
        'shwary_transaction_id',
        'client_nom',
        'client_email',
        'client_telephone',
        'montant',
        'montant_cdf',
        'montant_usd',
        'frais',
        'statut',
        'methode',
        'payload',
        'callback_data',
        'sms_envoye',
        'email_envoye',
        'sms_envoye_at',
        'email_envoye_at',
        'date_paiement',
        'date_validation',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'montant_cdf' => 'decimal:2',
        'montant_usd' => 'decimal:2',
        'frais' => 'decimal:2',
        'payload' => 'array',
        'callback_data' => 'array',
        'sms_envoye' => 'boolean',
        'email_envoye' => 'boolean',
        'date_paiement' => 'datetime',
        'date_validation' => 'datetime',
        'sms_envoye_at' => 'datetime',
        'email_envoye_at' => 'datetime',
    ];

    // ===== RELATIONS =====
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    // ===== ACCESSORS =====
    public function getStatutLabelAttribute()
    {
        return [
            'en_attente' => 'En attente ⏳',
            'paye' => 'Payé ✅',
            'echoue' => 'Échoué ❌',
            'annule' => 'Annulé ⛔',
            'rembourse' => 'Remboursé 🔄',
            'en_cours' => 'En cours ⏳',
        ][$this->statut] ?? 'En attente';
    }

    public function getMethodeLabelAttribute()
    {
        return [
            'carte' => 'Carte bancaire 💳',
            'paypal' => 'PayPal',
            'virement' => 'Virement bancaire',
            'stripe' => 'Stripe',
            'shwary' => 'Shwary',
            'mobile_money' => 'Mobile Money 📱',
            'orange_money' => '🧡 Orange Money',
            'airtel_money' => '❤️ Airtel Money',
            'mpesa' => '💚 M-Pesa',
        ][$this->methode] ?? $this->methode;
    }

    public function getMontantFormateAttribute()
    {
        $devise = $this->devise;
        if ($devise) {
            return $devise->symbole . ' ' . number_format($this->montant, 2, ',', ' ');
        }
        return number_format($this->montant, 2, ',', ' ') . ' FC';
    }

    /**
     * ✅ Raccourci pour obtenir le téléphone (du modèle ou de l'utilisateur)
     */
    public function getPhoneAttribute()
    {
        return $this->client_telephone ?? $this->user?->telephone ?? $this->user?->phone ?? null;
    }

    /**
     * ✅ Raccourci pour obtenir l'email
     */
    public function getEmailAttribute()
    {
        return $this->client_email ?? $this->user?->email ?? null;
    }

    /**
     * ✅ Raccourci pour obtenir le nom du client
     */
    public function getClientNameAttribute()
    {
        return $this->client_nom ?? $this->user?->name ?? null;
    }

    // ===== MÉTHODES =====
    public function isPaye()
    {
        return $this->statut === 'paye' || $this->statut === 'reussi';
    }

    public function isEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function isEchoue()
    {
        return $this->statut === 'echoue';
    }

    public function isAnnule()
    {
        return $this->statut === 'annule';
    }

    public function isRembourse()
    {
        return $this->statut === 'rembourse';
    }

    public function getMethodeIconAttribute()
    {
        return [
            'orange_money' => '🧡',
            'airtel_money' => '❤️',
            'mpesa' => '💚',
            'carte' => '💳',
            'paypal' => '💙',
            'virement' => '🏦',
            'shwary' => '🔷',
            'mobile_money' => '📱',
            'stripe' => '⚡',
        ][$this->methode] ?? '💳';
    }

    /**
     * Formater le téléphone pour l'affichage
     */
    public function getPhoneFormattedAttribute()
    {
        $phone = $this->phone;
        if (!$phone) return null;
        
        // Nettoyer le numéro
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Si le numéro fait 10 chiffres (RDC), formater
        if (strlen($phone) === 10) {
            $prefix = substr($phone, 0, 2);
            $middle = substr($phone, 2, 3);
            $suffix = substr($phone, 5);
            return '+243 ' . $prefix . ' ' . $middle . ' ' . $suffix;
        }
        
        return $phone;
    }
}