<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'oeuvre_id',
        'user_id',
        'commande_id',
        'type',
        'quantite',
        'stock_avant',
        'stock_apres',
        'motif',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'stock_avant' => 'integer',
        'stock_apres' => 'integer',
    ];

    // ============================================
    // RELATIONS
    // ============================================
    
    /**
     * L'œuvre concernée par ce mouvement de stock
     */
    public function oeuvre(): BelongsTo
    {
        return $this->belongsTo(Oeuvre::class);
    }

    /**
     * L'utilisateur qui a effectué ce mouvement (admin/artisan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La commande associée (si c'est une sortie liée à une commande)
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    // ============================================
    // SCOPES
    // ============================================
    
    /**
     * Filtrer les entrées de stock
     */
    public function scopeEntrees($query)
    {
        return $query->where('type', 'entree');
    }

    /**
     * Filtrer les sorties de stock
     */
    public function scopeSorties($query)
    {
        return $query->where('type', 'sortie');
    }

    /**
     * Filtrer les ajustements de stock
     */
    public function scopeAjustements($query)
    {
        return $query->where('type', 'ajustement');
    }

    /**
     * Filtrer par œuvre
     */
    public function scopeForOeuvre($query, $oeuvreId)
    {
        return $query->where('oeuvre_id', $oeuvreId);
    }

    /**
     * Filtrer par utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ============================================
    // ACCESSORS
    // ============================================
    
    /**
     * Libellé du type de mouvement
     */
    public function getTypeLabelAttribute(): string
    {
        return [
            'entree' => 'Entrée',
            'sortie' => 'Sortie',
            'ajustement' => 'Ajustement',
        ][$this->type] ?? $this->type;
    }

    /**
     * Couleur associée au type de mouvement
     */
    public function getTypeColorAttribute(): string
    {
        return [
            'entree' => 'success',
            'sortie' => 'danger',
            'ajustement' => 'warning',
        ][$this->type] ?? 'secondary';
    }

    /**
     * Icône associée au type de mouvement
     */
    public function getTypeIconAttribute(): string
    {
        return [
            'entree' => 'fa-arrow-down',
            'sortie' => 'fa-arrow-up',
            'ajustement' => 'fa-edit',
        ][$this->type] ?? 'fa-circle';
    }

    /**
     * Variation de stock (positive pour entrée, négative pour sortie)
     */
    public function getVariationAttribute(): int
    {
        if ($this->type === 'entree') {
            return $this->quantite;
        }
        return -$this->quantite;
    }

    // ============================================
    // MÉTHODES UTILITAIRES
    // ============================================
    
    /**
     * Créer un mouvement d'entrée de stock
     */
    public static function createEntree($oeuvreId, $quantite, $motif = null, $userId = null, $commandeId = null)
    {
        $oeuvre = Oeuvre::find($oeuvreId);
        $stockAvant = $oeuvre->stock;
        
        $oeuvre->increment('stock', $quantite);
        
        return self::create([
            'oeuvre_id' => $oeuvreId,
            'user_id' => $userId,
            'commande_id' => $commandeId,
            'type' => 'entree',
            'quantite' => $quantite,
            'stock_avant' => $stockAvant,
            'stock_apres' => $oeuvre->stock,
            'motif' => $motif,
        ]);
    }

    /**
     * Créer un mouvement de sortie de stock
     */
    public static function createSortie($oeuvreId, $quantite, $motif = null, $userId = null, $commandeId = null)
    {
        $oeuvre = Oeuvre::find($oeuvreId);
        $stockAvant = $oeuvre->stock;
        
        $oeuvre->decrement('stock', $quantite);
        
        return self::create([
            'oeuvre_id' => $oeuvreId,
            'user_id' => $userId,
            'commande_id' => $commandeId,
            'type' => 'sortie',
            'quantite' => $quantite,
            'stock_avant' => $stockAvant,
            'stock_apres' => $oeuvre->stock,
            'motif' => $motif,
        ]);
    }

    /**
     * Vérifier si le stock est suffisant
     */
    public static function isStockSuffisant($oeuvreId, $quantite): bool
    {
        $oeuvre = Oeuvre::find($oeuvreId);
        return $oeuvre && $oeuvre->stock >= $quantite;
    }
}