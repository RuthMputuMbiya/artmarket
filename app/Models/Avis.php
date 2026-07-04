<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $fillable = [
        'user_id',
        'oeuvre_id',
        'commande_id',
        'note',
        'commentaire',
        'is_approved',
    ];

    protected $casts = [
        'note' => 'integer',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oeuvre()
    {
        return $this->belongsTo(Oeuvre::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function isApproved()
    {
        return $this->is_approved;
    }
}