<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devise extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'symbole',
        'taux_change',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'taux_change' => 'decimal:6',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function oeuvres()
    {
        return $this->hasMany(Oeuvre::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function getSymboleAttribute($value)
    {
        return $value;
    }

    public function getTauxChangeAttribute($value)
    {
        return (float) $value;
    }
}