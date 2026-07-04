<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favoris extends Model
{
    /**
     * Le nom de la table associée au modèle.
     */
    protected $table = 'favoris';

    /**
     * Les attributs qui sont mass assignable.
     */
    protected $fillable = [
        'user_id',
        'oeuvre_id',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONS
    // ============================================================

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'œuvre
     */
    public function oeuvre()
    {
        return $this->belongsTo(Oeuvre::class);
    }
}