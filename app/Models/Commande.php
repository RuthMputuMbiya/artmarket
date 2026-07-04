<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'user_id',
        'devise_id',
        'reference',
        'total_ht',
        'total_ttc',
        'frais_livraison',
        'commission',
        'total_cdf',
        'total_usd',
        'statut',
        'adresse_livraison',
        'ville_livraison',
        'code_postal_livraison',
        'pays_livraison',
        'notes',
        'date_livraison',
    ];

    protected $casts = [
        'total_ht' => 'decimal:2',
        'total_ttc' => 'decimal:2',
        'frais_livraison' => 'decimal:2',
        'commission' => 'decimal:2',
        'total_cdf' => 'decimal:2',
        'total_usd' => 'decimal:2',
        'date_livraison' => 'datetime',
    ];

    // ===== RELATIONS =====
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    // ===== ACCESSORS =====
    public function getStatutLabelAttribute()
    {
        return [
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'expediee' => 'Expédiée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
            'rembourse' => 'Remboursée',
        ][$this->statut] ?? 'En attente';
    }

    public function getTotalFormateAttribute()
    {
        $devise = $this->devise;
        if ($devise) {
            return $devise->symbole . ' ' . number_format($this->total_ttc, 2, ',', ' ');
        }
        return number_format($this->total_ttc, 2, ',', ' ') . ' €';
    }

    // ===== MÉTHODES =====
    public function isPayee()
    {
        return $this->paiements()->where('statut', 'paye')->exists();
    }

    public function isLivree()
    {
        return $this->statut === 'livree';
    }

    public function isAnnulee()
    {
        return $this->statut === 'annulee';
    }
}