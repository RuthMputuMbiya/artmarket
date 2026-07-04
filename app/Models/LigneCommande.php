<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    protected $fillable = [
        'commande_id',
        'oeuvre_id',
        'quantite',
        'prix_unitaire',
        'total',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'total' => 'decimal:2',
        'quantite' => 'integer',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function oeuvre()
    {
        return $this->belongsTo(Oeuvre::class);
    }

    public function getPrixUnitaireFormateAttribute()
    {
        $devise = $this->commande?->devise;
        if ($devise) {
            return $devise->symbole . ' ' . number_format($this->prix_unitaire, 2, ',', ' ');
        }
        return number_format($this->prix_unitaire, 2, ',', ' ') . ' €';
    }
}