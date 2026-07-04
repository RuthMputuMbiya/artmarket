<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    protected $fillable = [
        'user_id',
        'nom',
        'adresse',
        'complement',
        'ville',
        'code_postal',
        'pays',
        'telephone',
        'is_principal',
        'type',
    ];

    protected $casts = [
        'is_principal' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute()
    {
        return [
            'facturation' => 'Facturation',
            'livraison' => 'Livraison',
        ][$this->type] ?? $this->type;
    }

    public function getAdresseCompleteAttribute()
    {
        $complet = $this->adresse;
        if ($this->complement) {
            $complet .= ', ' . $this->complement;
        }
        $complet .= ', ' . $this->code_postal . ' ' . $this->ville;
        $complet .= ', ' . $this->pays;
        return $complet;
    }

    public function isFacturation()
    {
        return $this->type === 'facturation';
    }

    public function isLivraison()
    {
        return $this->type === 'livraison';
    }
}