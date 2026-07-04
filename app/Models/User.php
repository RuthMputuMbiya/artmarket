<?php

namespace App\Models;

// ============================================================
// IMPORTS OBLIGATOIRES
// ============================================================
use App\Models\Role;
use App\Models\Oeuvre;
use App\Models\Commande;
use App\Models\Avis;
use App\Models\Message;
use App\Models\Favoris;
use App\Models\Adresse;
use App\Models\Notification;
use App\Models\Paiement;
// ============================================================

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'telephone',
        'photo_profil',
        'bio',
        'entreprise',
        'siret',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'statut',
        'is_verified',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function oeuvres()
    {
        return $this->hasMany(Oeuvre::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function favoris()
    {
        return $this->hasMany(Favoris::class);
    }

    public function adresses()
    {
        return $this->hasMany(Adresse::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // ============================================================
    // MÉTHODES DE RÔLE
    // ============================================================

    public function isAdmin(): bool
    {
        return $this->role && $this->role->slug === 'admin';
    }

    public function isArtisan(): bool
    {
        return $this->role && $this->role->slug === 'artisan';
    }

    public function isClient(): bool
    {
        return $this->role && $this->role->slug === 'client';
    }

    public function hasRole(string $slug): bool
    {
        return $this->role && $this->role->slug === $slug;
    }

    public function canPublish(): bool
    {
        return $this->isArtisan() || $this->isAdmin();
    }

    // ============================================================
    // ✅ ACCESSORS POUR LA PHOTO DE PROFIL (CORRIGÉ)
    // ============================================================

    /**
     * ✅ Obtenir l'URL de la photo de profil
     */
    public function getPhotoProfilUrlAttribute()
    {
        // Si une photo existe
        if ($this->photo_profil) {
            // Si l'URL commence déjà par http, la retourner telle quelle
            if (str_starts_with($this->photo_profil, 'http')) {
                return $this->photo_profil;
            }
            // Sinon, ajouter le chemin de stockage
            return asset('storage/' . $this->photo_profil);
        }
        
        // Photo par défaut
        return asset('images/default-avatar.png');
    }

    /**
     * ✅ Alias pour photo_profil_url
     */
    public function getAvatarAttribute()
    {
        return $this->photo_profil_url;
    }

    public function getRoleLabelAttribute(): string
    {
        $roleSlug = $this->role->slug ?? 'client';
        return [
            'admin' => 'Administrateur',
            'artisan' => 'Artisan',
            'client' => 'Client',
        ][$roleSlug] ?? 'Client';
    }

    public function getStatutLabelAttribute(): string
    {
        return [
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'suspendu' => 'Suspendu',
        ][$this->statut] ?? 'Actif';
    }

    public function getAdresseCompleteAttribute(): string
    {
        $complet = '';
        if ($this->adresse) $complet .= $this->adresse;
        if ($this->ville) $complet .= ($complet ? ', ' : '') . $this->ville;
        if ($this->code_postal) $complet .= ($complet ? ' ' : '') . $this->code_postal;
        if ($this->pays) $complet .= ($complet ? ', ' : '') . $this->pays;
        return $complet ?: 'Adresse non renseignée';
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    // ============================================================
    // MÉTHODES UTILITAIRES
    // ============================================================

    public function getCommandesEnAttente()
    {
        return $this->commandes()->where('statut', 'en_attente')->get();
    }

    public function getNotificationsNonLues()
    {
        return $this->notifications()->where('is_read', false)->get();
    }

    public function getCountNotificationsNonLuesAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function getFavorisIdsAttribute(): array
    {
        return $this->favoris()->pluck('oeuvre_id')->toArray();
    }
}