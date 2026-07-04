<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    /**
     * Les attributs qui sont mass assignable.
     */
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'permissions',
        'is_default',
        'created_at',
        'updated_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'permissions' => 'array',
        'is_default' => 'boolean',
    ];

    /**
     * Les attributs qui doivent être cachés.
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // ============================================================
    // RELATIONS
    // ============================================================

    /**
     * Relation avec les utilisateurs ayant ce rôle
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ============================================================
    // MÉTHODES DE PERMISSION
    // ============================================================

    /**
     * Vérifier si le rôle a une permission spécifique
     */
    public function hasPermission(string $permission): bool
    {
        // L'administrateur a toutes les permissions
        if ($this->slug === 'admin') {
            return true;
        }

        // Vérifier si la permission existe dans le tableau
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Vérifier si le rôle a plusieurs permissions
     */
    public function hasPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Vérifier si le rôle a au moins une des permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    /**
     * Obtenir le libellé du rôle
     */
    public function getLabelAttribute(): string
    {
        return [
            'admin' => 'Administrateur',
            'artisan' => 'Artisan',
            'client' => 'Client',
        ][$this->slug] ?? $this->nom;
    }

    /**
     * Obtenir la liste des permissions formatée
     */
    public function getPermissionsListAttribute(): string
    {
        if ($this->slug === 'admin') {
            return 'Toutes les permissions';
        }

        $permissions = $this->permissions ?? [];
        if (empty($permissions)) {
            return 'Aucune permission';
        }

        return implode(', ', array_map(function($p) {
            return str_replace('_', ' ', ucfirst($p));
        }, $permissions));
    }

    /**
     * Vérifier si le rôle est par défaut
     */
    public function getIsDefaultAttribute(): bool
    {
        return (bool) $this->is_default;
    }

    // ============================================================
    // MÉTHODES STATIQUES
    // ============================================================

    /**
     * Récupérer le rôle par défaut (client)
     */
    public static function getDefaultRole(): ?self
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Récupérer le rôle admin
     */
    public static function getAdminRole(): ?self
    {
        return self::where('slug', 'admin')->first();
    }

    /**
     * Récupérer le rôle artisan
     */
    public static function getArtisanRole(): ?self
    {
        return self::where('slug', 'artisan')->first();
    }

    /**
     * Récupérer le rôle client
     */
    public static function getClientRole(): ?self
    {
        return self::where('slug', 'client')->first();
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope pour filtrer les rôles actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par slug
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}