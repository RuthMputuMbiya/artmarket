@extends('layouts.admin')

@section('title', 'Gestion des oeuvres')

@section('page-title', 'Oeuvres')

@section('content')

<style>
    /* ===== STATS ===== */
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 4px solid #6C3CE1;
        transition: all 0.3s ease;
    }

    .stat-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .stat-mini .number {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-mini .label {
        font-size: 12px;
        color: #95A5A6;
        margin-top: 2px;
    }

    .stat-mini .icon {
        font-size: 28px;
        display: block;
        margin-bottom: 4px;
    }

    /* ===== FILTRES ===== */
    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
        align-items: center;
        background: white;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .filters select, .filters input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
    }

    .filters select:focus, .filters input:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
        color: white;
    }

    .btn-secondary {
        background: #F0F2F5;
        color: #2D3436;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-secondary:hover {
        background: #E8ECF1;
        transform: translateY(-2px);
    }

    /* ===== GRILLE DES OEUVRES ===== */
    .oeuvres-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .oeuvre-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 1px solid #f0f2f5;
    }

    .oeuvre-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .oeuvre-card .image-container {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: #F8F9FA;
    }

    .oeuvre-card .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .oeuvre-card:hover .image-container img {
        transform: scale(1.05);
    }

    .oeuvre-card .image-container .badge-statut {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .badge-statut.brouillon { background: #95A5A6; }
    .badge-statut.en_attente { background: #F39C12; }
    .badge-statut.publiee { background: #2ECC71; }
    .badge-statut.archivee { background: #E74C3C; }

    .oeuvre-card .image-container .badge-featured {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #F39C12;
        color: white;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .oeuvre-card .image-container .badge-stock {
        position: absolute;
        bottom: 12px;
        right: 12px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .badge-stock.en-stock { background: #2ECC71; }
    .badge-stock.rupture { background: #E74C3C; }

    .oeuvre-card .oeuvre-body {
        padding: 16px 18px 18px;
    }

    .oeuvre-card .oeuvre-body .titre {
        font-size: 16px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .oeuvre-card .oeuvre-body .artisan {
        font-size: 13px;
        color: #95A5A6;
        margin-bottom: 8px;
    }

    .oeuvre-card .oeuvre-body .artisan i {
        margin-right: 4px;
    }

    .oeuvre-card .oeuvre-body .categorie {
        display: inline-block;
        background: #F0F2F5;
        padding: 2px 12px;
        border-radius: 50px;
        font-size: 11px;
        color: #6C3CE1;
        margin-bottom: 10px;
    }

    .oeuvre-card .oeuvre-body .separator {
        border: none;
        border-top: 1px solid #F0F0F0;
        margin: 10px 0;
    }

    .oeuvre-card .oeuvre-body .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 0;
    }

    .oeuvre-card .oeuvre-body .info-row .label {
        font-size: 12px;
        color: #95A5A6;
    }

    .oeuvre-card .oeuvre-body .info-row .value {
        font-size: 13px;
        font-weight: 600;
        color: #2D3436;
    }

    .oeuvre-card .oeuvre-body .info-row .value.prix {
        font-size: 18px;
        color: #6C3CE1;
    }

    .oeuvre-card .oeuvre-body .info-row .value.prix span {
        font-size: 13px;
        color: #95A5A6;
        text-decoration: line-through;
        margin-left: 6px;
        font-weight: 400;
    }

    .oeuvre-card .oeuvre-footer {
        padding: 12px 18px 16px;
        background: #F8F9FA;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        border-top: 1px solid #f0f2f5;
    }

    .oeuvre-card .oeuvre-footer .actions {
        display: flex;
        gap: 6px;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-action.view { background: #E8E0F8; color: #6C3CE1; }
    .btn-action.featured { background: #D4F4E6; color: #2ECC71; }
    .btn-action.unfeatured { background: #FDE8D0; color: #F39C12; }
    .btn-action.delete { background: #FCD4D4; color: #E74C3C; }
    .btn-action.status { background: #D4E8FC; color: #3498DB; }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.view:hover { background: #6C3CE1; color: white; }
    .btn-action.featured:hover { background: #2ECC71; color: white; }
    .btn-action.unfeatured:hover { background: #F39C12; color: white; }
    .btn-action.delete:hover { background: #E74C3C; color: white; }
    .btn-action.status:hover { background: #3498DB; color: white; }

    /* ===== PAGINATION ===== */
    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
        padding: 16px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .pagination-info .info {
        font-size: 13px;
        color: #95A5A6;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .oeuvres-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }

        .filters {
            flex-direction: column;
            align-items: stretch;
        }

        .filters form {
            flex-direction: column;
            width: 100%;
        }

        .filters form input,
        .filters form select {
            width: 100%;
        }

        .oeuvre-card .image-container {
            height: 180px;
        }
    }

    @media (max-width: 480px) {
        .oeuvres-grid {
            grid-template-columns: 1fr;
        }

        .stats-mini {
            grid-template-columns: 1fr 1fr;
        }

        .oeuvre-card .image-container {
            height: 200px;
        }

        .oeuvre-card .oeuvre-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .oeuvre-card .oeuvre-footer .actions {
            justify-content: center;
        }
    }
</style>

<!-- ===== STATS ===== -->
<div class="stats-mini">
    <div class="stat-mini" style="border-left-color:#6C3CE1;">
        <span class="icon"></span>
        <div class="number">{{ $oeuvres->total() }}</div>
        <div class="label">Total oeuvres</div>
    </div>
    <div class="stat-mini" style="border-left-color:#2ECC71;">
        <span class="icon"></span>
        <div class="number">{{ $oeuvres->where('statut', 'publiee')->count() }}</div>
        <div class="label">Publiees</div>
    </div>
    <div class="stat-mini" style="border-left-color:#F39C12;">
        <span class="icon"></span>
        <div class="number">{{ $oeuvres->where('statut', 'en_attente')->count() }}</div>
        <div class="label">En attente</div>
    </div>
    <div class="stat-mini" style="border-left-color:#95A5A6;">
        <span class="icon"></span>
        <div class="number">{{ $oeuvres->where('statut', 'brouillon')->count() }}</div>
        <div class="label">Brouillons</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
<div class="filters">
    <form action="{{ route('admin.oeuvres.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
        <input type="text" name="search" placeholder="Rechercher une oeuvre..." value="{{ request('search') }}"
               style="flex:1;min-width:180px;">

        <select name="statut">
            <option value="">Tous les statuts</option>
            @foreach($statuts as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                    {{ ucfirst($statut) }}
                </option>
            @endforeach
        </select>

        <select name="categorie">
            <option value="">Toutes les categories</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>

        <select name="artisan">
            <option value="">Tous les artisans</option>
            @foreach($artisans as $artisan)
                <option value="{{ $artisan->id }}" {{ request('artisan') == $artisan->id ? 'selected' : '' }}>
                    {{ $artisan->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-primary">
            Filtrer
        </button>

        <a href="{{ route('admin.oeuvres.index') }}" class="btn-secondary">
            Reinitialiser
        </a>
    </form>
</div>

<!-- ===== GRILLE DES OEUVRES ===== -->
<div class="oeuvres-grid">
    @forelse($oeuvres as $oeuvre)
        <div class="oeuvre-card">
            <!-- Image -->
            <div class="image-container">
                @php
                    $imageUrl = $oeuvre->image_principale ?? $oeuvre->image_principale_url ?? asset('images/placeholder.jpg');
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $oeuvre->titre }}" 
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                
                <!-- Badge Statut -->
                <span class="badge-statut {{ $oeuvre->statut }}">
                    {{ ucfirst($oeuvre->statut) }}
                </span>

                <!-- Badge Featured -->
                @if($oeuvre->is_featured)
                    <span class="badge-featured">
                         A la une
                    </span>
                @endif

                <!-- Badge Stock -->
                @if($oeuvre->stock > 0)
                    <span class="badge-stock en-stock">
                        En stock ({{ $oeuvre->stock }})
                    </span>
                @else
                    <span class="badge-stock rupture">
                        Rupture
                    </span>
                @endif
            </div>

            <!-- Body -->
            <div class="oeuvre-body">
                <div class="titre">{{ $oeuvre->titre }}</div>
                <div class="artisan">
                    <i class="fas fa-user"></i> {{ $oeuvre->artisan->name ?? 'Artisan' }}
                </div>

                <span class="categorie">{{ $oeuvre->categorie->nom ?? 'Non classe' }}</span>

                <hr class="separator">

                <div class="info-row">
                    <span class="label">Prix</span>
                    <span class="value prix">
                        {{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix_actuel, 0) }}
                        @if($oeuvre->prix_promo)
                            <span>{{ number_format($oeuvre->prix, 0) }}</span>
                        @endif
                    </span>
                </div>

                <div class="info-row">
                    <span class="label">Stock</span>
                    <span class="value" style="color:{{ $oeuvre->stock > 0 ? '#2ECC71' : '#E74C3C' }};">
                        {{ $oeuvre->stock > 0 ? $oeuvre->stock . ' disponible(s)' : 'Rupture' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="label">Date</span>
                    <span class="value">{{ $oeuvre->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="oeuvre-footer">
                <div style="font-size:12px;color:#95A5A6;">
                    <i class="fas fa-eye"></i> {{ $oeuvre->avis_count ?? 0 }}
                    <i class="fas fa-shopping-cart" style="margin-left:8px;"></i> {{ $oeuvre->commandes_count ?? 0 }}
                </div>
                <div class="actions">
                    <a href="{{ route('admin.oeuvres.show', $oeuvre->id) }}" class="btn-action view" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if($oeuvre->is_featured)
                        <a href="{{ route('admin.oeuvres.toggle-featured', $oeuvre->id) }}" class="btn-action unfeatured" title="Retirer la une">
                            <i class="fas fa-star"></i>
                        </a>
                    @else
                        <a href="{{ route('admin.oeuvres.toggle-featured', $oeuvre->id) }}" class="btn-action featured" title="Mettre en une">
                            <i class="fas fa-star"></i>
                        </a>
                    @endif

                    <form action="{{ route('admin.oeuvres.destroy', $oeuvre->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action delete" title="Supprimer" onclick="return confirm('Supprimer cette oeuvre ?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column:1/-1;text-align:center;padding:60px 20px;background:white;border-radius:12px;">
            <i class="fas fa-palette" style="font-size:48px;color:#D4D4D4;display:block;margin-bottom:16px;"></i>
            <h3 style="font-size:18px;color:#2D3436;margin-bottom:8px;">Aucune oeuvre trouvee</h3>
            <p style="color:#95A5A6;">Aucune oeuvre ne correspond a vos criteres</p>
        </div>
    @endforelse
</div>

<!-- ===== PAGINATION ===== -->
<div class="pagination-info">
    <div class="info">
        Affichage de {{ $oeuvres->firstItem() ?? 0 }} a {{ $oeuvres->lastItem() ?? 0 }} sur {{ $oeuvres->total() }} oeuvres
    </div>
    <div>
        {{ $oeuvres->appends(request()->query())->links() }}
    </div>
</div>

@endsection