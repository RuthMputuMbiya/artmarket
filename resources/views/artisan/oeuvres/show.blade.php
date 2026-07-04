@extends('layouts.artisan')

@section('title', 'Détails de l\'œuvre - ' . $oeuvre->titre)

@section('page-title', 'Détails de l\'œuvre')

@section('content')

<style>
    .oeuvre-detail {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .oeuvre-detail .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #F0F0F0;
    }

    .oeuvre-detail .header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
    }

    .oeuvre-detail .header .statut {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
    }

    .statut-brouillon { background: #F0F0F0; color: #7F8C8D; }
    .statut-en_attente { background: #FDE8D0; color: #F39C12; }
    .statut-publiee { background: #D4E8FC; color: #3498DB; }
    .statut-archivee { background: #FCD4D4; color: #E74C3C; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        padding: 16px;
        background: #F8F9FA;
        border-radius: 12px;
    }

    .info-grid .item .label {
        font-size: 12px;
        color: #95A5A6;
        display: block;
    }

    .info-grid .item .value {
        font-size: 15px;
        font-weight: 600;
        color: #2D3436;
        margin-top: 2px;
    }

    .info-grid .item .value.prix {
        color: #6C3CE1;
        font-size: 20px;
    }

    .gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .gallery img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        transition: all 0.3s ease;
    }

    .gallery img:hover {
        border-color: #6C3CE1;
        transform: scale(1.02);
    }

    .gallery .principal {
        border-color: #6C3CE1;
        position: relative;
    }

    .gallery .principal::after {
        content: 'Principale';
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

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
        color: white;
    }

    .btn-secondary {
        background: #F0F2F5;
        color: #2D3436;
        border: 2px solid #E8ECF1;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary:hover {
        background: #E8ECF1;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #E74C3C;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-danger:hover {
        background: #C0392B;
        transform: translateY(-2px);
        color: white;
    }

    .actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #F0F0F0;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
        .gallery {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
        .actions {
            flex-direction: column;
        }
        .actions .btn-primary,
        .actions .btn-secondary,
        .actions .btn-danger {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        .oeuvre-detail .header h1 {
            font-size: 20px;
        }
    }
</style>

<div class="oeuvre-detail">

    <!-- ===== HEADER ===== -->
    <div class="header">
        <div>
            <h1>{{ $oeuvre->titre }}</h1>
            <div style="margin-top:4px;color:#95A5A6;font-size:14px;">
                <i class="fas fa-tag"></i> {{ $oeuvre->categorie->nom ?? 'Non classe' }}
                <span style="margin:0 8px;">|</span>
                <i class="fas fa-user"></i> {{ $oeuvre->artisan->name ?? 'Artisan' }}
            </div>
        </div>
        <div>
            <span class="statut statut-{{ $oeuvre->statut }}">{{ ucfirst($oeuvre->statut) }}</span>
            @if($oeuvre->is_featured)
                <span style="margin-left:8px;background:#FFD93D;color:#2D3436;padding:4px 12px;border-radius:50px;font-size:12px;font-weight:600;">
                    A la une
                </span>
            @endif
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="stats-mini">
        <div class="stat-mini" style="border-left-color:#3498DB;">
            <div class="number">{{ $stats['total_avis'] ?? 0 }}</div>
            <div class="label">Avis</div>
        </div>
        <div class="stat-mini" style="border-left-color:#F39C12;">
            <div class="number">{{ $stats['moyenne_notes'] ?? 0 }} ★</div>
            <div class="label">Note moyenne</div>
        </div>
        <div class="stat-mini" style="border-left-color:#2ECC71;">
            <div class="number">{{ $stats['total_commandes'] ?? 0 }}</div>
            <div class="label">Commandes</div>
        </div>
        <div class="stat-mini" style="border-left-color:#6C3CE1;">
            <div class="number">{{ number_format($stats['total_ventes'] ?? 0, 0) }} FC</div>
            <div class="label">Ventes totales</div>
        </div>
    </div>

    <!-- ===== INFOS ===== -->
    <div class="info-grid">
        <div class="item">
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
        <div class="item">
            <span class="label">Stock</span>
            <span class="value" style="color:{{ $oeuvre->stock > 0 ? '#2ECC71' : '#E74C3C' }};">
                {{ $oeuvre->stock > 0 ? $oeuvre->stock . ' disponible(s)' : 'Rupture' }}
                @if($oeuvre->stock <= $oeuvre->stock_min && $oeuvre->stock > 0)
                    <span style="color:#F39C12;font-size:12px;">(Stock bas)</span>
                @endif
            </span>
        </div>
        <div class="item">
            <span class="label">Materiaux</span>
            <span class="value">{{ $oeuvre->materiaux ?? 'Non renseigne' }}</span>
        </div>
        <div class="item">
            <span class="label">Dimensions</span>
            <span class="value">{{ $oeuvre->dimensions ?? 'Non renseigne' }}</span>
        </div>
        <div class="item">
            <span class="label">Date de creation</span>
            <span class="value">{{ $oeuvre->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}</span>
        </div>
        @if($oeuvre->published_at)
        <div class="item">
            <span class="label">Date de publication</span>
            <span class="value">{{ $oeuvre->published_at->locale('fr')->isoFormat('DD MMM YYYY') }}</span>
        </div>
        @endif
    </div>

    <!-- ===== DESCRIPTION ===== -->
    <div style="margin-bottom:24px;">
        <h3 style="font-size:16px;font-weight:700;color:#2D3436;margin-bottom:8px;">Description</h3>
        <p style="color:#2D3436;font-size:14px;line-height:1.8;">
            {{ $oeuvre->description ?? 'Aucune description' }}
        </p>
    </div>

    <!-- ===== IMAGES ===== -->
    @if($oeuvre->images->count() > 0)
        <div style="margin-bottom:24px;">
            <h3 style="font-size:16px;font-weight:700;color:#2D3436;margin-bottom:12px;">
                Images ({{ $oeuvre->images->count() }})
                <span style="font-weight:400;font-size:12px;color:#95A5A6;margin-left:8px;">
                    L'image principale est marquee
                </span>
            </h3>
            <div class="gallery">
                @foreach($oeuvre->images as $image)
                    <div class="{{ $image->is_principal ? 'principal' : '' }}" style="position:relative;">
                        <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $oeuvre->titre }}"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        @if($image->is_principal)
                            <span style="position:absolute;bottom:8px;right:8px;background:#6C3CE1;color:white;padding:2px 10px;border-radius:50px;font-size:10px;font-weight:600;">
                                Principale
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div style="margin-bottom:24px;text-align:center;padding:40px 20px;background:#F8F9FA;border-radius:12px;color:#95A5A6;">
            <i class="fas fa-image" style="font-size:48px;display:block;margin-bottom:12px;"></i>
            Aucune image pour cette oeuvre
        </div>
    @endif

    <!-- ===== ACTIONS ===== -->
    <div class="actions">
        <a href="{{ route('artisan.oeuvres.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <a href="{{ route('artisan.oeuvres.edit', $oeuvre->id) }}" class="btn-primary">
            <i class="fas fa-edit"></i> Modifier
        </a>
        @if($oeuvre->is_featured)
            <a href="{{ route('artisan.oeuvres.toggle-featured', $oeuvre->id) }}" class="btn-secondary">
                <i class="fas fa-star"></i> Retirer de la une
            </a>
        @else
            <a href="{{ route('artisan.oeuvres.toggle-featured', $oeuvre->id) }}" class="btn-primary">
                <i class="fas fa-star"></i> Mettre en une
            </a>
        @endif
        @if($oeuvre->statut !== 'archivee')
            <form action="{{ route('artisan.oeuvres.change-status', $oeuvre->id) }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="statut" value="archivee">
                <button type="submit" class="btn-danger" onclick="return confirm('Archiver cette oeuvre ?')">
                    <i class="fas fa-archive"></i> Archiver
                </button>
            </form>
        @endif
    </div>

</div>

@endsection