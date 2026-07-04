@extends('layouts.artisan')

@section('title', 'Mes oeuvres')

@section('page-title', 'Mes oeuvres')

@section('content')

<style>
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 14px 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 3px solid #6C3CE1;
    }

    .stat-mini .number {
        font-size: 20px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-mini .label {
        font-size: 11px;
        color: #95A5A6;
    }

    .stat-mini .icon { font-size: 18px; display:block; margin-bottom:2px; }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        align-items: center;
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

    .oeuvre-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border: 1px solid rgba(108,60,225,0.06);
    }

    .oeuvre-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
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
        top: 12px;
        left: 12px;
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

    .badge-featured { background: #FFD93D; color: #2D3436; }
    .badge-stock { background: #2ECC71; color: white; }
    .badge-stock-low { background: #F39C12; color: white; }
    .badge-stock-out { background: #E74C3C; color: white; }

    .oeuvre-card .image .actions-top {
        position: absolute;
        top: 12px;
        right: 12px;
        display: flex;
        gap: 4px;
    }

    .oeuvre-card .image .actions-top .btn-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: rgba(255,255,255,0.9);
        color: #2D3436;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .oeuvre-card .image .actions-top .btn-icon:hover {
        background: white;
        transform: scale(1.1);
    }

    .oeuvre-card .image .actions-top .btn-icon.delete:hover { color: #E74C3C; }
    .oeuvre-card .image .actions-top .btn-icon.edit:hover { color: #3498DB; }
    .oeuvre-card .image .actions-top .btn-icon.duplicate:hover { color: #2ECC71; }

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

    .oeuvre-card .body .stock {
        font-size: 12px;
        margin-top: 4px;
    }

    .oeuvre-card .body .stock .ok { color: #2ECC71; }
    .oeuvre-card .body .stock .low { color: #F39C12; }
    .oeuvre-card .body .stock .out { color: #E74C3C; }

    .oeuvre-card .footer {
        padding: 12px 16px;
        border-top: 1px solid #F0F0F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #95A5A6;
    }

    .oeuvre-card .footer .statut {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 600;
    }

    .statut-brouillon { background: #F0F0F0; color: #7F8C8D; }
    .statut-en_attente { background: #FDE8D0; color: #F39C12; }
    .statut-publiee { background: #D4E8FC; color: #3498DB; }
    .statut-archivee { background: #FCD4D4; color: #E74C3C; }

    .btn-export {
        background: #2D3436;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-export:hover {
        background: #1a1a1a;
        transform: translateY(-2px);
        color: white;
    }

    .grid-oeuvres {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    @media (max-width: 768px) {
        .stats-mini {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        }

        .grid-oeuvres {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .grid-oeuvres {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- ===== STATS ===== -->
<div class="stats-mini">
    <div class="stat-mini" style="border-color:#6C3CE1;">
        <div class="icon"></div>
        <div class="number">{{ $stats['total'] ?? 0 }}</div>
        <div class="label">Total</div>
    </div>
    <div class="stat-mini" style="border-color:#3498DB;">
        <div class="icon"></div>
        <div class="number">{{ $stats['publiees'] ?? 0 }}</div>
        <div class="label">Publiees</div>
    </div>
    <div class="stat-mini" style="border-color:#7F8C8D;">
        <div class="icon"></div>
        <div class="number">{{ $stats['brouillons'] ?? 0 }}</div>
        <div class="label">Brouillons</div>
    </div>
    <div class="stat-mini" style="border-color:#F39C12;">
        <div class="icon"></div>
        <div class="number">{{ $stats['en_attente'] ?? 0 }}</div>
        <div class="label">En attente</div>
    </div>
    <div class="stat-mini" style="border-color:#E74C3C;">
        <div class="icon"></div>
        <div class="number">{{ $stats['stock_total'] ?? 0 }}</div>
        <div class="label">Stock total</div>
    </div>
    <div class="stat-mini" style="border-color:#2ECC71;">
        <div class="icon"></div>
        <div class="number">{{ $stats['ventes'] ?? 0 }}</div>
        <div class="label">Ventes</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
<div class="filters">
    <form action="{{ route('artisan.oeuvres.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
        <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" style="flex:1;min-width:150px;">

        <select name="statut">
            <option value="">Tous les statuts</option>
            @foreach($statuts as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ ucfirst($statut) }}</option>
            @endforeach
        </select>

        <select name="categorie">
            <option value="">Toutes les categories</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>{{ $categorie->nom }}</option>
            @endforeach
        </select>

        <select name="stock">
            <option value="">Tous les stocks</option>
            <option value="disponible" {{ request('stock') == 'disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="rupture" {{ request('stock') == 'rupture' ? 'selected' : '' }}>Rupture</option>
        </select>

        <button type="submit" class="btn-primary" style="padding:8px 20px;font-size:13px;">Filtrer</button>

        <a href="{{ route('artisan.oeuvres.index') }}" class="btn-secondary" style="padding:8px 20px;font-size:13px;">Reinitialiser</a>
    </form>

</div>

<!-- ===== GRILLE ===== -->
<div class="grid-oeuvres">
    @forelse($oeuvres as $oeuvre)
        <div class="oeuvre-card">
            <div class="image">
                <!-- ✅ CORRECTION: Utiliser Storage::url() -->
                @php
                    $imageUrl = $oeuvre->image_principale_url ?? asset('images/placeholder.jpg');
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $oeuvre->titre }}" 
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">

                <div class="badges">
                    @if($oeuvre->is_featured)
                        <span class="badge badge-featured">A la une</span>
                    @endif
                    @if($oeuvre->stock > 10)
                        <span class="badge badge-stock">En stock</span>
                    @elseif($oeuvre->stock > 0)
                        <span class="badge badge-stock-low">Stock faible</span>
                    @else
                        <span class="badge badge-stock-out">Rupture</span>
                    @endif
                </div>

                <div class="actions-top">
                    <a href="{{ route('artisan.oeuvres.edit', $oeuvre->id) }}" class="btn-icon edit" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('artisan.oeuvres.duplicate', $oeuvre->id) }}" class="btn-icon duplicate" title="Dupliquer" onclick="return confirm('Dupliquer cette oeuvre ?')">
                        <i class="fas fa-copy"></i>
                    </a>
                    <form action="{{ route('artisan.oeuvres.destroy', $oeuvre->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer cette oeuvre ?')" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="body">
                <div class="titre">{{ $oeuvre->titre }}</div>
                <div class="categorie">{{ $oeuvre->categorie->nom ?? 'Non classe' }}</div>
                <div>
                    <span class="prix">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix_actuel, 0) }}</span>
                    @if($oeuvre->prix_promo)
                        <span class="prix-ancien">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix, 0) }}</span>
                    @endif
                </div>
                <div class="stock">
                    @if($oeuvre->stock > 0)
                        <span class="ok">{{ $oeuvre->stock }} en stock</span>
                    @else
                        <span class="out">Rupture de stock</span>
                    @endif
                </div>
            </div>

            <div class="footer">
                <span class="statut statut-{{ $oeuvre->statut }}">{{ ucfirst($oeuvre->statut) }}</span>
                <span>{{ $oeuvre->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}</span>
                <a href="{{ route('artisan.oeuvres.show', $oeuvre->id) }}" style="color:#6C3CE1;text-decoration:none;font-weight:500;">
                    Voir
                </a>
            </div>
        </div>
    @empty
        <div style="grid-column:1/-1;text-align:center;padding:60px;color:#95A5A6;">
            <i class="fas fa-palette" style="font-size:48px;display:block;margin-bottom:16px;"></i>
            <h3 style="font-size:20px;color:#2D3436;margin-bottom:8px;">Aucune oeuvre</h3>
            <p>Commencez par creer votre premiere oeuvre</p>
            <a href="{{ route('artisan.oeuvres.create') }}" class="btn-primary" style="margin-top:16px;">
                Creer une oeuvre
            </a>
        </div>
    @endforelse
</div>

<!-- ===== PAGINATION ===== -->
<div style="margin-top:24px;">
    {{ $oeuvres->appends(request()->query())->links() }}
</div>

@endsection