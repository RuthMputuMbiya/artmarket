<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = [
        'oeuvre_id',
        'url',
        'url_thumbnail',
        'titre',
        'ordre',
        'is_principal',
    ];

    protected $casts = [
        'is_principal' => 'boolean',
        'ordre' => 'integer',
    ];

    // ============================================================
    // RELATIONS
    // ============================================================

    public function oeuvre()
    {
        return $this->belongsTo(Oeuvre::class);
    }

    // ============================================================
    // ACCESSORS - ✅ CORRIGÉS
    // ============================================================

    /**
     * Obtenir l'URL complète de l'image - ✅ CORRIGÉ
     */
    public function getUrlAttribute($value)
    {
        // Si l'URL commence déjà par http, la retourner
        if (str_starts_with($value, 'http')) {
            return $value;
        }
        
        // ✅ Si l'URL commence par /storage/, l'enlever
        if (str_starts_with($value, '/storage/')) {
            $value = substr($value, 9);
        }
        
        //  Utiliser Storage::url() pour générer le bon lien
        return Storage::url($value);
    }

    /**
     * Obtenir l'URL de la miniature - CORRIGÉ
     */
    public function getUrlThumbnailAttribute($value)
    {
        if (!$value) {
            return $this->url;
        }
        if (str_starts_with($value, 'http')) {
            return $value;
        }
        if (str_starts_with($value, '/storage/')) {
            $value = substr($value, 9);
        }
        return Storage::url($value);
    }

    /**
     * Obtenir l'URL brute (sans traitement)
     */
    public function getRawUrlAttribute()
    {
        return $this->attributes['url'] ?? null;
    }
}