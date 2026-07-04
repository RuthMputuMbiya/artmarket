@extends('layouts.admin')

@section('title', 'Details de l\'oeuvre - ' . $oeuvre->titre)

@section('page-title', 'Details de l\'oeuvre')

@section('content')

<style>
    .oeuvre-detail {
        max-width: 1200px;
        margin: 0 auto;
    }

    .oeuvre-detail .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .oeuvre-detail .header .info h1 {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 4px;
    }

    .oeuvre-detail .header .info .meta {
        font-size: 14px;
        color: #95A5A6;
    }

    .oeuvre-detail .header .info .meta i {
        margin-right: 4px;
    }

    .badge-status {
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.brouillon { background: #F0F0F0; color: #7F8C8D; }
    .badge-status.en_attente { background: #FDE8D0; color: #F39C12; }
    .badge-status.publiee { background: #D4F4E6; color: #2ECC71; }
    .badge-status.archivee { background: #FCD4D4; color: #E74C3C; }

    .badge-featured {
        background: #F39C12;
        color: white;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action.back { background: #F0F2F5; color: #2D3436; }
    .btn-action.edit { background: #D4E8FC; color: #3498DB; }
    .btn-action.delete { background: #FCD4D4; color: #E74C3C; }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.back:hover { background: #E8ECF1; }
    .btn-action.edit:hover { background: #3498DB; color: white; }
    .btn-action.delete:hover { background: #E74C3C; color: white; }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .card .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #F0F0F0;
    }

    .card .info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #F8F9FA;
    }

    .card .info-row:last-child {
        border-bottom: none;
    }

    .card .info-row .label {
        color: #95A5A6;
        font-size: 13px;
    }

    .card .info-row .value {
        font-weight: 600;
        color: #2D3436;
        font-size: 14px;
    }

    .card .info-row .value.prix {
        color: #6C3CE1;
        font-size: 18px;
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .image-gallery img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        transition: all 0.3s ease;
    }

    .image-gallery img:hover {
        border-color: #6C3CE1;
        transform: scale(1.02);
    }

    .image-gallery .principal {
        border-color: #6C3CE1;
        position: relative;
    }

    .image-gallery .principal::after {
        content: 'Principal';
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: #6C3CE1;
        color: white;
        padding: 2px 10px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 600;
    }

    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 4px solid #6C3CE1;
    }

    .stat-mini .number {
        font-size: 22px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-mini .label {
        font-size: 12px;
        color: #95A5A6;
        margin-top: 2px;
    }

    .stat-mini .icon {
        font-size: 24px;
        display: block;
        margin-bottom: 4px;
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }

        .oeuvre-detail .header {
            flex-direction: column;
        }

        .image-gallery {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .stats-mini {
            grid-template-columns: 1fr 1fr;
        }

        .image-gallery {
            grid-template-columns: 1fr 1fr;
        }

        .image-gallery img {
            height: 120px;
        }
    }
</style>

<div class="oeuvre-detail">

    <!-- ===== HEADER ===== -->
    <div class="header">
        <div class="info">
            <h1>{{ $oeuvre->titre }}</h1>
            <div class="meta">
                <i class="fas fa-user"></i> {{ $oeuvre->artisan->name ?? 'Artisan' }}
                <span style="margin:0 8px;">|</span>
                <i class="fas fa-tag"></i> {{ $oeuvre->categorie->nom ?? 'Non classe' }}
                <span style="margin:0 8px;">|</span>
                <i class="fas fa-calendar"></i> {{ $oeuvre->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}
            </div>
            <div style="margin-top:8px;">
                <span class="badge-status {{ $oeuvre->statut }}">{{ ucfirst($oeuvre->statut) }}</span>
                @if($oeuvre->is_featured)
                    <span class="badge-featured" style="margin-left:8px;">⭐ A la une</span>
                @endif
                @if($oeuvre->stock > 0)
                    <span style="margin-left:8px;color:#2ECC71;font-weight:600;">En stock ({{ $oeuvre->stock }})</span>
                @else
                    <span style="margin-left:8px;color:#E74C3C;font-weight:600;">Rupture de stock</span>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('admin.oeuvres.index') }}" class="btn-action back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('admin.oeuvres.edit', $oeuvre->id) }}" class="btn-action edit">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <form action="{{ route('admin.oeuvres.destroy', $oeuvre->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action delete" onclick="return confirm('Supprimer cette oeuvre ?')">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="stats-mini">
        <div class="stat-mini" style="border-left-color:#3498DB;">
            <span class="icon"></span>
            <div class="number">{{ $stats['moyenne_notes'] ?? 0 }}</div>
            <div class="label">Note moyenne</div>
        </div>
        <div class="stat-mini" style="border-left-color:#6C3CE1;">
            <span class="icon"></span>
            <div class="number">{{ $stats['total_avis'] ?? 0 }}</div>
            <div class="label">Avis</div>
        </div>
        <div class="stat-mini" style="border-left-color:#2ECC71;">
            <span class="icon"></span>
            <div class="number">{{ $stats['total_commandes'] ?? 0 }}</div>
            <div class="label">Commandes</div>
        </div>
        <div class="stat-mini" style="border-left-color:#F39C12;">
            <span class="icon"></span>
            <div class="number">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($stats['total_ventes'] ?? 0, 0) }}</div>
            <div class="label">Ventes totales</div>
        </div>
    </div>

    <!-- ===== GRID ===== -->
    <div class="grid-2">

        <!-- ===== INFORMATIONS ===== -->
        <div class="card">
            <div class="card-title">Informations</div>

            <div class="info-row">
                <span class="label">Titre</span>
                <span class="value">{{ $oeuvre->titre }}</span>
            </div>
            <div class="info-row">
                <span class="label">Artisan</span>
                <span class="value">{{ $oeuvre->artisan->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Categorie</span>
                <span class="value">{{ $oeuvre->categorie->nom ?? 'Non classe' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Devise</span>
                <span class="value">{{ $oeuvre->devise->code ?? 'FC' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Prix</span>
                <span class="value prix">
                    {{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix_actuel, 2) }}
                    @if($oeuvre->prix_promo)
                        <span style="font-size:14px;color:#95A5A6;text-decoration:line-through;margin-left:8px;">
                            {{ number_format($oeuvre->prix, 2) }}
                        </span>
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
                <span class="label">Statut</span>
                <span class="value">{{ ucfirst($oeuvre->statut) }}</span>
            </div>
            <div class="info-row">
                <span class="label">A la une</span>
                <span class="value">{{ $oeuvre->is_featured ? 'Oui' : 'Non' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Date de creation</span>
                <span class="value">{{ $oeuvre->created_at->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}</span>
            </div>
            @if($oeuvre->published_at)
            <div class="info-row">
                <span class="label">Date de publication</span>
                <span class="value">{{ $oeuvre->published_at->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}</span>
            </div>
            @endif
        </div>

        <!-- ===== DESCRIPTION ===== -->
        <div class="card">
            <div class="card-title">Description</div>
            <div style="color:#2D3436;font-size:14px;line-height:1.8;">
                {{ $oeuvre->description ?? 'Aucune description' }}
            </div>

            @if($oeuvre->materiaux || $oeuvre->dimensions)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #F0F0F0;">
                    @if($oeuvre->materiaux)
                        <div class="info-row">
                            <span class="label">Materiaux</span>
                            <span class="value">{{ $oeuvre->materiaux }}</span>
                        </div>
                    @endif
                    @if($oeuvre->dimensions)
                        <div class="info-row">
                            <span class="label">Dimensions</span>
                            <span class="value">{{ $oeuvre->dimensions }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- ===== IMAGES ===== -->
        <div class="card" style="grid-column:1/3;">
            <div class="card-title">
                Images ({{ $oeuvre->images->count() }})
                @if($oeuvre->images->count() > 0)
                    <span style="font-weight:400;font-size:12px;color:#95A5A6;margin-left:8px;">
                        L'image principale est marquee
                    </span>
                @endif
            </div>
            @if($oeuvre->images->count() > 0)
                <div class="image-gallery">
                    @foreach($oeuvre->images as $image)
                        <div class="{{ $image->is_principal ? 'principal' : '' }}" style="position:relative;">
                            <img src="{{ $image->url }}" alt="{{ $oeuvre->titre }}" 
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                            @if($image->is_principal)
                                <span style="position:absolute;bottom:8px;right:8px;background:#6C3CE1;color:white;padding:2px 10px;border-radius:50px;font-size:10px;font-weight:600;">
                                    Principal
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:40px 20px;color:#95A5A6;">
                    <i class="fas fa-image" style="font-size:48px;display:block;margin-bottom:12px;"></i>
                    Aucune image pour cette oeuvre
                </div>
            @endif
        </div>

    </div>

</div>

@endsection