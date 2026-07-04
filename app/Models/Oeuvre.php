<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Oeuvre extends Model
{
    protected $fillable = [
        'user_id',
        'categorie_id',
        'devise_id',
        'titre',
        'slug',
        'description',
        'prix',
        'prix_promo',
        'prix_cdf',
        'prix_usd',
        'materiaux',
        'dimensions',
        'stock',
        'stock_min',
        'stock_max',
        'quantite_par_commande',
        'statut',
        'is_featured',
        'is_available',
        'published_at',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'prix_promo' => 'decimal:2',
        'prix_cdf' => 'decimal:2',
        'prix_usd' => 'decimal:2',
        'stock' => 'integer',
        'stock_min' => 'integer',
        'stock_max' => 'integer',
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
        'published_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONS
    // ============================================================

    public function artisan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function commandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function favoris()
    {
        return $this->hasMany(Favoris::class);
    }

    // ============================================================
    // RELATION AVEC LES MOUVEMENTS DE STOCK
    // ============================================================
    
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    // ============================================================
    // ACCESSORS POUR LES IMAGES - ✅ CORRIGÉS
    // ============================================================

    /**
     * Obtenir l'image principale de l'oeuvre
     */
    public function getImagePrincipaleAttribute()
    {
        $image = $this->images()->where('is_principal', true)->first();
        if ($image) {
            return $image->url;
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->url;
        }
        
        return null;
    }

    /**
     * Obtenir l'URL complète de l'image principale - ✅ CORRIGÉ
     */
    public function getImagePrincipaleUrlAttribute()
    {
        $image = $this->image_principale;
        if ($image) {
            // Si l'URL commence déjà par http
            if (str_starts_with($image, 'http')) {
                return $image;
            }
            // ✅ Si l'URL commence par /storage/, l'enlever
            if (str_starts_with($image, '/storage/')) {
                $image = substr($image, 9);
            }
            // Utiliser Storage::url()
            return Storage::url($image);
        }
        return asset('images/placeholder.jpg');
    }

    /**
     * Obtenir toutes les images de l'oeuvre
     */
    public function getImagesAllAttribute()
    {
        return $this->images;
    }

    /**
     * Obtenir toutes les URLs des images - ✅ CORRIGÉ
     */
    public function getImagesUrlsAttribute()
    {
        return $this->images->map(function($image) {
            $url = $image->url;
            if (str_starts_with($url, 'http')) {
                return $url;
            }
            if (str_starts_with($url, '/storage/')) {
                $url = substr($url, 9);
            }
            return Storage::url($url);
        });
    }

    /**
     * Obtenir le prix actuel (avec promo ou normal)
     */
    public function getPrixActuelAttribute()
    {
        return $this->prix_promo ?? $this->prix;
    }

    public function getMoyenneNotesAttribute()
    {
        return $this->avis()->where('is_approved', true)->avg('note') ?? 0;
    }

    public function getNombreAvisAttribute()
    {
        return $this->avis()->where('is_approved', true)->count();
    }

    public function getStatutLabelAttribute()
    {
        return [
            'brouillon' => 'Brouillon',
            'en_attente' => 'En attente',
            'publiee' => 'Publiee',
            'archivee' => 'Archivee',
        ][$this->statut] ?? 'Brouillon';
    }

    // ============================================================
    // ACCESSORS POUR LE STOCK
    // ============================================================
    
    public function getStockCalculeAttribute(): int
    {
        $entrees = $this->stocks()->where('type', 'entree')->sum('quantite');
        $sorties = $this->stocks()->where('type', 'sortie')->sum('quantite');
        return $entrees - $sorties;
    }

    public function isStockBas($seuil = null): bool
    {
        $seuil = $seuil ?? ($this->stock_min ?? 5);
        return $this->stock <= $seuil;
    }

    public function isStockEpuise(): bool
    {
        return $this->stock <= 0;
    }

    public function hasStockSuffisant(int $quantite): bool
    {
        return $this->stock >= $quantite;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Epuise';
        }
        if ($this->stock <= ($this->stock_min ?? 5)) {
            return 'Stock bas';
        }
        return 'En stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'danger';
        }
        if ($this->stock <= ($this->stock_min ?? 5)) {
            return 'warning';
        }
        return 'success';
    }

    // ============================================================
    // METHODES
    // ============================================================

    public function isPublished()
    {
        return $this->statut === 'publiee';
    }

    public function isAvailable()
    {
        return $this->is_available && $this->stock > 0;
    }

    // ============================================================
    // METHODES DE GESTION DE STOCK
    // ============================================================
    
    public function addStock(int $quantite, string $motif = null, $userId = null, $commandeId = null): Stock
    {
        return Stock::createEntree(
            $this->id,
            $quantite,
            $motif,
            $userId,
            $commandeId
        );
    }

    public function removeStock(int $quantite, string $motif = null, $userId = null, $commandeId = null): Stock
    {
        if (!$this->hasStockSuffisant($quantite)) {
            throw new \Exception("Stock insuffisant pour l'oeuvre {$this->titre}");
        }
        
        return Stock::createSortie(
            $this->id,
            $quantite,
            $motif,
            $userId,
            $commandeId
        );
    }
}