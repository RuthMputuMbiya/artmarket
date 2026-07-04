@extends('layouts.client')

@section('title', 'Catalogue - ArtMarket')

@section('content')

<style>
    /* ============================================
               STATS MINI
            ============================================ */
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 14px 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border-left: 3px solid #6C3CE1;
        transition: all 0.3s ease;
    }

    .stat-mini:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .stat-mini .number {
        font-size: 18px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-mini .label {
        font-size: 11px;
        color: #95A5A6;
    }

    .stat-mini .icon {
        font-size: 16px;
        display: block;
        margin-bottom: 2px;
    }

    /* ============================================
               FILTRES SIDEBAR
            ============================================ */
    .filters-sidebar {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 90px;
    }

    .filters-sidebar .filter-group {
        margin-bottom: 16px;
    }

    .filters-sidebar .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 6px;
    }

    .filters-sidebar .filter-group select,
    .filters-sidebar .filter-group input {
        width: 100%;
        padding: 8px 12px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
    }

    .filters-sidebar .filter-group select:focus,
    .filters-sidebar .filter-group input:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108, 60, 225, 0.1);
    }

    .filters-sidebar .filter-group .range-inputs {
        display: flex;
        gap: 8px;
    }

    .filters-sidebar .filter-group .range-inputs input {
        width: 50%;
    }

    .btn-filter {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 60, 225, 0.35);
    }

    .btn-filter-reset {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        background: white;
        color: #2D3436;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 8px;
    }

    .btn-filter-reset:hover {
        border-color: #6C3CE1;
        color: #6C3CE1;
    }

    /* ============================================
               CARTE ŒUVRE
            ============================================ */
    .oeuvre-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
        border: 1px solid rgba(108, 60, 225, 0.06);
    }

    .oeuvre-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .oeuvre-card .image {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #F8F9FA;
    }

    .oeuvre-card .image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .oeuvre-card .image:hover img {
        transform: scale(1.05);
    }

    .oeuvre-card .image .badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .oeuvre-card .image .badges .badge {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-featured {
        background: #FFD93D;
        color: #2D3436;
    }
    .badge-stock {
        background: #2ECC71;
        color: white;
    }
    .badge-stock-out {
        background: #E74C3C;
        color: white;
    }
    .badge-promo {
        background: #FF6B6B;
        color: white;
    }

    .oeuvre-card .image .favorite-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.9);
        color: #95A5A6;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .oeuvre-card .image .favorite-btn:hover {
        transform: scale(1.1);
        background: white;
    }

    .oeuvre-card .image .favorite-btn.active {
        color: #E74C3C;
        background: #FCD4D4;
    }

    .oeuvre-card .body {
        padding: 16px;
    }

    .oeuvre-card .body .titre {
        font-size: 16px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .oeuvre-card .body .categorie {
        font-size: 12px;
        color: #95A5A6;
        margin-bottom: 8px;
    }

    .oeuvre-card .body .prix {
        font-size: 20px;
        font-weight: 700;
        color: #6C3CE1;
    }

    .oeuvre-card .body .prix-ancien {
        font-size: 14px;
        color: #E74C3C;
        text-decoration: line-through;
        margin-left: 8px;
    }

    .oeuvre-card .body .artisan {
        font-size: 12px;
        color: #95A5A6;
        margin-top: 4px;
    }

    .oeuvre-card .footer {
        padding: 12px 16px;
        border-top: 1px solid #F0F0F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .oeuvre-card .footer .note {
        color: #F39C12;
        font-size: 13px;
        font-weight: 600;
    }

    .oeuvre-card .footer .btn-add {
        padding: 6px 16px;
        border-radius: 50px;
        border: none;
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        font-weight: 600;
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .oeuvre-card .footer .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 60, 225, 0.35);
    }

    .oeuvre-card .footer .btn-add:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .oeuvre-card .footer .btn-view {
        padding: 6px 16px;
        border-radius: 50px;
        border: 2px solid #E8ECF1;
        background: white;
        color: #2D3436;
        font-weight: 600;
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .oeuvre-card .footer .btn-view:hover {
        border-color: #6C3CE1;
        color: #6C3CE1;
    }

    /* ============================================
               GRILLE
            ============================================ */
    .grid-oeuvres {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }

    /* ============================================
               CATÉGORIE TAG
            ============================================ */
    .categorie-tag {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 50px;
        background: #E8E0F8;
        color: #6C3CE1;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 2px;
    }

    .categorie-tag:hover {
        background: #6C3CE1;
        color: white;
    }

    .categorie-tag.active {
        background: #6C3CE1;
        color: white;
    }

    .tri-select {
        padding: 8px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        background: white;
    }

    .tri-select:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108, 60, 225, 0.1);
    }

    /* ============================================
               RESPONSIVE
            ============================================ */
    @media (max-width: 768px) {
        .filters-sidebar {
            position: relative;
            top: 0;
            margin-bottom: 20px;
        }

        .grid-oeuvres {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }

        .stats-mini {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .grid-oeuvres {
            grid-template-columns: 1fr;
        }
    }

    /* ============================================
               UTILITAIRES
            ============================================ */
    .hidden {
        display: none !important;
    }

    .block {
        display: block !important;
    }

    .text-center {
        text-align: center;
    }

    .mt-2 {
        margin-top: 8px;
    }
    .mt-3 {
        margin-top: 12px;
    }
    .mt-4 {
        margin-top: 16px;
    }
    .mb-2 {
        margin-bottom: 8px;
    }
    .mb-3 {
        margin-bottom: 12px;
    }
    .mb-4 {
        margin-bottom: 16px;
    }
    .mb-6 {
        margin-bottom: 24px;
    }

    .gap-1 {
        gap: 4px;
    }
    .gap-2 {
        gap: 8px;
    }
    .gap-3 {
        gap: 12px;
    }
    .gap-4 {
        gap: 16px;
    }
    .gap-6 {
        gap: 24px;
    }

    .flex {
        display: flex;
    }
    .flex-wrap {
        flex-wrap: wrap;
    }
    .items-center {
        align-items: center;
    }
    .justify-between {
        justify-content: space-between;
    }
    .justify-center {
        justify-content: center;
    }
    .justify-end {
        justify-content: flex-end;
    }

    .w-full {
        width: 100%;
    }
    .w-auto {
        width: auto;
    }
    .flex-1 {
        flex: 1;
    }
    .flex-none {
        flex: none;
    }

    .grid {
        display: grid;
    }
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }
    .gap-6 {
        gap: 24px;
    }

    .md\:col-span-1 {
        grid-column: span 1;
    }
    .md\:col-span-3 {
        grid-column: span 3;
    }

    @media (min-width: 768px) {
        .md\:grid-cols-4 {
            grid-template-columns: repeat(4, 1fr);
        }
        .md\:hidden {
            display: none !important;
        }
        .md\:block {
            display: block !important;
        }
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 60, 225, 0.35);
        color: white;
    }

    .btn-secondary {
        background: white;
        color: #2D3436;
        border: 2px solid #E8ECF1;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-secondary:hover {
        border-color: #6C3CE1;
        color: #6C3CE1;
    }

    .text-primary {
        color: #6C3CE1;
    }
    .text-gray-800 {
        color: #2D3436;
    }
    .text-gray-500 {
        color: #95A5A6;
    }
    .text-gray-400 {
        color: #b0b8c0;
    }
    .text-white {
        color: white;
    }

    .font-bold {
        font-weight: 700;
    }
    .font-semibold {
        font-weight: 600;
    }
    .font-medium {
        font-weight: 500;
    }

    .text-3xl {
        font-size: 30px;
    }
    .text-2xl {
        font-size: 24px;
    }
    .text-xl {
        font-size: 20px;
    }
    .text-lg {
        font-size: 18px;
    }
    .text-sm {
        font-size: 13px;
    }
    .text-xs {
        font-size: 11px;
    }

    .bg-white {
        background: white;
    }
    .rounded-2xl {
        border-radius: 16px;
    }
    .rounded-xl {
        border-radius: 12px;
    }
    .shadow {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    }
</style>

<!-- ============================================
STATS
============================================ -->
<div class="stats-mini">
    <div class="stat-mini" style="border-color: #6C3CE1;">
        <div class="icon"></div>
        <div class="number">{{ $stats['total'] ?? 0 }}</div>
        <div class="label">Œuvres</div>
    </div>
    <div class="stat-mini" style="border-color: #3498DB;">
        <div class="icon"></div>
        <div class="number">{{ $stats['categories'] ?? 0 }}</div>
        <div class="label">Catégories</div>
    </div>
    <div class="stat-mini" style="border-color: #2ECC71;">
        <div class="icon"></div>
        <div class="number">{{ $stats['artisans'] ?? 0 }}</div>
        <div class="label">Artisans</div>
    </div>
    <div class="stat-mini" style="border-color: #F39C12;">
        <div class="icon"></div>
        <div class="number">{{ number_format($stats['prix_min'] ?? 0, 0) }} - {{ number_format($stats['prix_max'] ?? 0, 0) }}</div>
        <div class="label">Prix (min - max)</div>
    </div>
</div>

<!-- ============================================
FILTRES MOBILE TOGGLE
============================================ -->
<div class="flex justify-between items-center mb-4 flex-wrap gap-2">
    <button class="btn-secondary md:hidden" onclick="document.getElementById('filtersMobile').classList.toggle('hidden')">
        <i class="fas fa-filter"></i> Filtres
    </button>

    <div class="flex items-center gap-2">
        <span class="text-sm text-gray-500">Trier:</span>
        <select class="tri-select" id="triSelect" onchange="applyTri()">
            <option value="recent" {{ request('tri') == 'recent' ? 'selected' : '' }}>Plus récent</option>
            <option value="prix_asc" {{ request('tri') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
            <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
            <option value="populaire" {{ request('tri') == 'populaire' ? 'selected' : '' }}>Plus populaire</option>
            <option value="mieux_note" {{ request('tri') == 'mieux_note' ? 'selected' : '' }}>Mieux noté</option>
        </select>
    </div>
</div>

<!-- ============================================
MAIN GRID
============================================ -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <!-- ===== SIDEBAR FILTRES ===== -->
    <div class="md:col-span-1">
        <div class="filters-sidebar" id="filtersMobile">
            <form action="{{ route('client.catalogue') }}" method="GET" id="filterForm">

                <!-- Recherche -->
                <div class="filter-group">
                    <label for="search">🔍 Rechercher</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Rechercher une œuvre...">
                </div>

                <!-- Catégorie -->
                <div class="filter-group">
                    <label for="categorie">Catégorie</label>
                    <select id="categorie" name="categorie">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories ?? [] as $categorie)
                            <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->icone ?? '' }} {{ $categorie->nom }} ({{ $categorie->oeuvres_count ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Prix -->
                <div class="filter-group">
                    <label>Prix</label>
                    <div class="range-inputs">
                        <input type="number" name="prix_min" placeholder="Min" value="{{ request('prix_min') }}">
                        <input type="number" name="prix_max" placeholder="Max" value="{{ request('prix_max') }}">
                    </div>
                </div>

                <!-- Options -->
                <div class="filter-group">
                    <div class="flex flex-wrap gap-2">
                        <label class="flex items-center gap-1 text-sm text-gray-800 cursor-pointer">
                            <input type="checkbox" name="disponible" value="1" {{ request('disponible') ? 'checked' : '' }}>
                            Disponible
                        </label>
                        <label class="flex items-center gap-1 text-sm text-gray-800 cursor-pointer">
                            <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }}>
                            À la une
                        </label>
                    </div>
                </div>

                <!-- Catégories rapides -->
                <div class="filter-group">
                    <label>Catégories populaires</label>
                    <div class="flex flex-wrap gap-1">
                        @foreach(($categories ?? []) as $cat)
                            <span class="categorie-tag {{ request('categorie') == $cat->id ? 'active' : '' }}"
                                  onclick="document.getElementById('categorie').value='{{ $cat->id }}';document.getElementById('filterForm').submit();">
                                {{ $cat->icone ?? '' }} {{ $cat->nom }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Appliquer les filtres
                </button>
                <button type="button" class="btn-filter-reset" onclick="resetFilters()">
                    <i class="fas fa-undo"></i> Réinitialiser
                </button>
            </form>
        </div>
    </div>

    <!-- ===== GRILLE ŒUVRES ===== -->
    <div class="md:col-span-3">
        <div class="grid-oeuvres" id="oeuvresGrid">
            @forelse($oeuvres ?? [] as $oeuvre)
                <div class="oeuvre-card">
                    <div class="image">
                        <img src="{{ $oeuvre->image_principale_url ?? asset('images/placeholder.jpg') }}"
                             alt="{{ $oeuvre->titre }}" loading="lazy">

                        <div class="badges">
                            @if($oeuvre->is_featured)
                                <span class="badge badge-featured"><i class="fas fa-star"></i> À la une</span>
                            @endif
                            @if($oeuvre->stock > 0)
                                <span class="badge badge-stock"><i class="fas fa-check"></i> En stock</span>
                            @else
                                <span class="badge badge-stock-out"><i class="fas fa-times"></i> Rupture</span>
                            @endif
                            @if($oeuvre->prix_promo)
                                <span class="badge badge-promo">-{{ round((1 - $oeuvre->prix_promo/$oeuvre->prix)*100) }}%</span>
                            @endif
                        </div>

                        <button class="favorite-btn {{ in_array($oeuvre->id, $favoris ?? []) ? 'active' : '' }}"
                                onclick="toggleFavori({{ $oeuvre->id }})"
                                title="Ajouter aux favoris">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>

                    <div class="body">
                        <div class="titre">{{ $oeuvre->titre }}</div>
                        <div class="categorie">
                            <i class="fas fa-tag"></i> {{ $oeuvre->categorie->nom ?? 'Non classé' }}
                        </div>
                        <div>
                            <span class="prix">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix_actuel, 0) }}</span>
                            @if($oeuvre->prix_promo)
                                <span class="prix-ancien">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix, 0) }}</span>
                            @endif
                        </div>
                        <div class="artisan">
                            <i class="fas fa-user"></i> {{ $oeuvre->artisan->name ?? 'Artisan' }}
                        </div>
                    </div>

                    <div class="footer">
                        <div>
                            @php
                                $moyenne = $oeuvre->avis()->where('is_approved', true)->avg('note') ?? 0;
                            @endphp
                            @if($moyenne > 0)
                                <span class="note">{{ number_format($moyenne, 1) }} ★</span>
                            @else
                                <span class="text-xs text-gray-400">Pas d'avis</span>
                            @endif
                        </div>
                        <div class="flex gap-1">
                            <a href="{{ route('client.oeuvre.show', $oeuvre->slug) }}" class="btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($oeuvre->stock > 0)
                               <form action="/client/panier/add" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="oeuvre_id" value="{{ $oeuvre->id }}">
                                    <input type="hidden" name="quantite" value="1">
                                    <button type="submit" class="btn-add">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            @else
                                <button class="btn-add" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center" style="grid-column:1/-1;padding:60px 20px;background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.04);">
                    <i class="fas fa-search" style="font-size:60px;color:#D4D4D4;display:block;margin-bottom:16px;"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Aucune œuvre trouvée</h3>
                    <p class="text-gray-500 mb-4">Essayez de modifier vos filtres ou votre recherche.</p>
                    <button class="btn-primary" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Réinitialiser les filtres
                    </button>
                </div>
            @endforelse
        </div>

        <!-- ===== PAGINATION ===== -->
        <div class="mt-4">
            {{ $oeuvres->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- ============================================
SCRIPTS
============================================ -->
<script>
    // ===== TOGGLE FAVORI =====
    function toggleFavori(id) {
        fetch(`/client/favoris/toggle/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const btn = document.querySelector(`.favorite-btn[onclick*="${id}"]`);
                if (btn) {
                    btn.classList.toggle('active');
                }
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // ===== TRI =====
    function applyTri() {
        const tri = document.getElementById('triSelect').value;
        const url = new URL(window.location.href);
        url.searchParams.set('tri', tri);
        window.location.href = url.toString();
    }

    // ===== RESET FILTRES =====
    function resetFilters() {
        window.location.href = '{{ route('client.catalogue') }}';
    }

    // ===== RECHERCHE AUTO =====
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });

    // ===== FILTRES MOBILE =====
    document.addEventListener('DOMContentLoaded', function() {
        const filtersMobile = document.getElementById('filtersMobile');
        if (window.innerWidth < 768) {
            filtersMobile.classList.add('hidden');
        }
    });
</script>

@endsection