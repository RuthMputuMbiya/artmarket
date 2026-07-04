@extends('layouts.client')

@section('title', 'Mes favoris - ArtMarket')

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 14px 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 3px solid #6C3CE1;
    }

    .stat-card .number {
        font-size: 20px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-card .label {
        font-size: 11px;
        color: #95A5A6;
    }

    .stat-card .icon { font-size: 16px; display:block; margin-bottom:2px; }

    .favori-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border: 1px solid rgba(108,60,225,0.06);
        position: relative;
    }

    .favori-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .favori-card .image {
        height: 200px;
        overflow: hidden;
        background: #F8F9FA;
        position: relative;
    }

    .favori-card .image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .favori-card .image:hover img {
        transform: scale(1.05);
    }

    .favori-card .image .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: rgba(231,76,60,0.9);
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .favori-card .image .remove-btn:hover {
        transform: scale(1.1);
        background: #E74C3C;
    }

    .favori-card .body {
        padding: 16px;
    }

    .favori-card .body .titre {
        font-size: 16px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .favori-card .body .artisan {
        font-size: 13px;
        color: #95A5A6;
        margin-bottom: 8px;
    }

    .favori-card .body .prix {
        font-size: 20px;
        font-weight: 700;
        color: #6C3CE1;
    }

    .favori-card .body .prix-ancien {
        font-size: 14px;
        color: #E74C3C;
        text-decoration: line-through;
        margin-left: 8px;
    }

    .favori-card .footer {
        padding: 12px 16px;
        border-top: 1px solid #F0F0F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .favori-card .footer .btn-add {
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

    .favori-card .footer .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
    }

    .favori-card .footer .btn-add:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .favori-card .footer .btn-view {
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

    .favori-card .footer .btn-view:hover {
        border-color: #6C3CE1;
        color: #6C3CE1;
    }

    .grid-favoris {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        grid-column: 1/-1;
    }

    .empty-state .icon {
        font-size: 60px;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #2D3436;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #95A5A6;
        margin-bottom: 16px;
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        align-items: center;
    }

    .filters input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
        flex: 1;
        min-width: 150px;
    }

    .filters input:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.1);
    }

    .clear-btn {
        color: #E74C3C;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.3s ease;
    }

    .clear-btn:hover {
        color: #C0392B;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .grid-favoris {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .grid-favoris {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-heart text-primary mr-3"></i> Mes favoris
        </h1>
        @if($favoris->total() > 0)
            <form action="{{ route('client.favoris.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="clear-btn" onclick="return confirm('Vider tous vos favoris ?')">
                    <i class="fas fa-trash"></i> Vider les favoris
                </button>
            </form>
        @endif
    </div>

    <!-- ===== STATS ===== -->
    <div class="stats-grid">
        <div class="stat-card" style="border-color:#E74C3C;">
            <div class="icon"></div>
            <div class="number">{{ $stats['total'] }}</div>
            <div class="label">Total favoris</div>
        </div>
        <div class="stat-card" style="border-color:#6C3CE1;">
            <div class="icon"></div>
            <div class="number">{{ $stats['categories'] }}</div>
            <div class="label">Catégories</div>
        </div>
    </div>

    <!-- ===== RECHERCHE ===== -->
    <div class="filters">
        <form action="{{ route('client.favoris.index') }}" method="GET" style="display:flex;flex:1;gap:12px;">
            <input type="text" name="search" placeholder="🔍 Rechercher dans mes favoris..." value="{{ request('search') }}">
            <button type="submit" class="btn-primary" style="padding:8px 20px;font-size:13px;">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('client.favoris.index') }}" class="btn-secondary" style="padding:8px 20px;font-size:13px;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- ===== GRILLE ===== -->
    <div class="grid-favoris">
        @forelse($favoris as $favori)
            @php $oeuvre = $favori->oeuvre; @endphp
            <div class="favori-card">
                <div class="image">
                    <img src="{{ $oeuvre->image_principale ?? asset('images/placeholder.jpg') }}"
                         alt="{{ $oeuvre->titre }}" loading="lazy">

                    <form action="{{ route('client.favoris.destroy', $favori->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-btn" onclick="return confirm('Retirer cette œuvre des favoris ?')">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>

                <div class="body">
                    <div class="titre">{{ $oeuvre->titre }}</div>
                    <div class="artisan">
                        <i class="fas fa-user"></i> {{ $oeuvre->artisan->name ?? 'Artisan' }}
                    </div>
                    <div>
                        <span class="prix">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix_actuel, 0) }}</span>
                        @if($oeuvre->prix_promo)
                            <span class="prix-ancien">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix, 0) }}</span>
                        @endif
                    </div>
                </div>

                <div class="footer">
                    <div style="font-size:12px;color:#95A5A6;">
                        @if($oeuvre->stock > 0)
                            <span style="color:#2ECC71;"><i class="fas fa-check-circle"></i> En stock</span>
                        @else
                            <span style="color:#E74C3C;"><i class="fas fa-times-circle"></i> Rupture</span>
                        @endif
                    </div>
                    <div style="display:flex;gap:4px;">
                        <a href="{{ route('client.oeuvre.show', $oeuvre->slug) }}" class="btn-view">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        @if($oeuvre->stock > 0)
                            <form action="{{ route('client.panier.add') }}" method="POST" style="display:inline;">
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
            <div class="empty-state">
                <div class="icon">❤️</div>
                <h3>Aucun favori</h3>
                <p>Vous n'avez pas encore ajouté d'œuvres à vos favoris.</p>
                <a href="{{ route('client.catalogue') }}" class="btn-primary">
                    <i class="fas fa-compass"></i> Explorer le catalogue
                </a>
            </div>
        @endforelse
    </div>

    <!-- ===== PAGINATION ===== -->
    <div style="margin-top:24px;">
        {{ $favoris->appends(request()->query())->links() }}
    </div>
</div>

@endsection