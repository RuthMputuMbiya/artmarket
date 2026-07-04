<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Categorie extends Model
{
    protected $fillable = [
        'parent_id',
        'nom',
        'slug',
        'description',
        'icone',
        'image',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============================================================
    // RELATIONS
    // ============================================================

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'parent_id');
    }

    public function enfants(): HasMany
    {
        return $this->hasMany(Categorie::class, 'parent_id');
    }

    public function oeuvres(): HasMany
    {
        return $this->hasMany(Oeuvre::class);
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getIconeAttribute($value)
    {
        if ($value && str_starts_with($value, 'http')) {
            return $value;
        }
        return $value ?? '📁';
    }

    public function getIconeHtmlAttribute()
    {
        $icone = $this->icone;
        if (preg_match('/[\p{Emoji}]/u', $icone)) {
            return $icone;
        }
        if ($icone && str_starts_with($icone, 'http')) {
            return '<img src="' . $icone . '" alt="' . $this->nom . '" style="width:24px;height:24px;object-fit:contain;">';
        }
        return '📁';
    }

    public function getNomCompletAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->nom . ' - ' . $this->nom;
        }
        return $this->nom;
    }

    public function getUrlAttribute(): string
    {
        return route('client.categorie', $this->slug);
    }

    public function getNombreOeuvresAttribute(): int
    {
        return $this->oeuvres()->where('is_available', true)->count();
    }

    public function getNombreTotalOeuvresAttribute(): int
    {
        $total = $this->oeuvres()->where('is_available', true)->count();
        
        foreach ($this->enfants as $enfant) {
            $total += $enfant->oeuvres()->where('is_available', true)->count();
        }
        
        return $total;
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActifs($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithEnfants($query)
    {
        return $query->with(['enfants' => function($q) {
            $q->where('is_active', true)->orderBy('order');
        }]);
    }

    // ============================================================
    // MÉTHODES
    // ============================================================

    public static function getCategoriesAvecSousCategories()
    {
        return self::with(['enfants' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }])->whereNull('parent_id')
          ->where('is_active', true)
          ->orderBy('order')
          ->get();
    }

    public function hasEnfants(): bool
    {
        return $this->enfants()->where('is_active', true)->exists();
    }

    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function isEnfant(): bool
    {
        return !is_null($this->parent_id);
    }
}