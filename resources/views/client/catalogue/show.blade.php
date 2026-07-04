@extends('layouts.client')

@section('title', $oeuvre->titre . ' - ArtMarket')

@section('content')

<style>
    .oeuvre-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        margin-bottom: 32px;
    }

    .oeuvre-detail .gallery {
        position: sticky;
        top: 90px;
    }

    .oeuvre-detail .gallery .main-image {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 16px;
        overflow: hidden;
        background: #F8F9FA;
        border: 2px solid #E8ECF1;
    }

    .oeuvre-detail .gallery .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .oeuvre-detail .gallery .thumbnails {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 8px;
        margin-top: 12px;
    }

    .oeuvre-detail .gallery .thumbnails img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #E8ECF1;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .oeuvre-detail .gallery .thumbnails img:hover {
        border-color: #6C3CE1;
    }

    .oeuvre-detail .gallery .thumbnails img.active {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.2);
    }

    .oeuvre-detail .info .titre {
        font-size: 28px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 8px;
    }

    .oeuvre-detail .info .artisan {
        font-size: 15px;
        color: #95A5A6;
        margin-bottom: 12px;
    }

    .oeuvre-detail .info .artisan a {
        color: #6C3CE1;
        text-decoration: none;
    }

    .oeuvre-detail .info .artisan a:hover {
        text-decoration: underline;
    }

    .oeuvre-detail .info .prix {
        font-size: 32px;
        font-weight: 700;
        color: #6C3CE1;
        margin-bottom: 4px;
    }

    .oeuvre-detail .info .prix-ancien {
        font-size: 20px;
        color: #E74C3C;
        text-decoration: line-through;
        margin-left: 12px;
    }

    .oeuvre-detail .info .stock {
        margin-bottom: 16px;
    }

    .oeuvre-detail .info .stock .ok { color: #2ECC71; }
    .oeuvre-detail .info .stock .out { color: #E74C3C; }

    .oeuvre-detail .info .description {
        color: #2D3436;
        line-height: 1.8;
        margin-bottom: 16px;
        padding: 16px 0;
        border-top: 1px solid #F0F0F0;
        border-bottom: 1px solid #F0F0F0;
    }

    .oeuvre-detail .info .caracteristiques {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 24px;
        margin-bottom: 16px;
    }

    .oeuvre-detail .info .caracteristiques .item .label {
        font-size: 12px;
        color: #95A5A6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .oeuvre-detail .info .caracteristiques .item .value {
        font-weight: 500;
        color: #2D3436;
    }

    .oeuvre-detail .info .actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .oeuvre-detail .info .actions .btn-add {
        padding: 14px 36px;
        border-radius: 50px;
        border: none;
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        font-weight: 600;
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        justify-content: center;
    }

    .oeuvre-detail .info .actions .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
    }

    .oeuvre-detail .info .actions .btn-add:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .oeuvre-detail .info .actions .btn-favorite {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        border: 2px solid #E8ECF1;
        background: white;
        color: #95A5A6;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .oeuvre-detail .info .actions .btn-favorite:hover {
        border-color: #E74C3C;
        color: #E74C3C;
    }

    .oeuvre-detail .info .actions .btn-favorite.active {
        border-color: #E74C3C;
        color: #E74C3C;
        background: #FCD4D4;
    }

    .note-section {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }

    .note-section .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .note-section .header .moyenne {
        font-size: 32px;
        font-weight: 700;
        color: #2D3436;
    }

    .note-section .header .moyenne span {
        font-size: 16px;
        color: #95A5A6;
        font-weight: 400;
    }

    .note-section .header .etoiles {
        font-size: 24px;
        color: #F39C12;
    }

    .note-section .distribution {
        margin-top: 12px;
    }

    .note-section .distribution .bar-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .note-section .distribution .bar-item .label {
        min-width: 30px;
        color: #2D3436;
    }

    .note-section .distribution .bar-item .bar {
        flex: 1;
        height: 6px;
        background: #F0F0F0;
        border-radius: 10px;
        overflow: hidden;
    }

    .note-section .distribution .bar-item .bar .fill {
        height: 100%;
        background: #F39C12;
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .note-section .distribution .bar-item .count {
        min-width: 30px;
        color: #95A5A6;
        text-align: right;
    }

    .avis-item {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 4px solid #F39C12;
    }

    .avis-item .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 4px;
    }

    .avis-item .header .client {
        font-weight: 600;
        color: #2D3436;
    }

    .avis-item .header .note {
        color: #F39C12;
        font-weight: 600;
    }

    .avis-item .header .date {
        font-size: 12px;
        color: #95A5A6;
    }

    .avis-item .commentaire {
        color: #2D3436;
        line-height: 1.6;
    }

    .similaires-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .similaire-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border: 1px solid rgba(108,60,225,0.06);
    }

    .similaire-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .similaire-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .similaire-card .body {
        padding: 12px 16px;
    }

    .similaire-card .body .titre {
        font-weight: 600;
        color: #2D3436;
        font-size: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .similaire-card .body .prix {
        font-weight: 700;
        color: #6C3CE1;
        font-size: 16px;
    }

    @media (max-width: 992px) {
        .oeuvre-detail {
            grid-template-columns: 1fr;
        }

        .oeuvre-detail .gallery {
            position: relative;
            top: 0;
        }

        .oeuvre-detail .info .caracteristiques {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .oeuvre-detail .info .actions .btn-add {
            flex: none;
            width: 100%;
        }

        .similaires-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
</style>

<!-- ===== DÉTAILS ŒUVRE ===== -->
<div class="oeuvre-detail">

    <!-- ===== GALERIE ===== -->
    <div class="gallery">
        <div class="main-image">
            <img id="mainImage" src="{{ $oeuvre->image_principale ?? asset('images/placeholder.jpg') }}"
                 alt="{{ $oeuvre->titre }}">
        </div>

        @if($oeuvre->images->count() > 1)
            <div class="thumbnails">
                @foreach($oeuvre->images as $image)
                    <img src="{{ $image->url }}"
                         alt="Image"
                         class="{{ $image->is_principal ? 'active' : '' }}"
                         onclick="changeMainImage(this.src, this)">
                @endforeach
            </div>
        @endif
    </div>

    <!-- ===== INFORMATIONS ===== -->
    <div class="info">
        <h1 class="titre">{{ $oeuvre->titre }}</h1>

        <div class="artisan">
            <i class="fas fa-user"></i>
            <a href="#">{{ $oeuvre->artisan->name ?? 'Artisan' }}</a>
            <span style="margin-left:8px;">·</span>
            <span style="margin-left:8px;">
                <i class="fas fa-tag"></i> {{ $oeuvre->categorie->nom ?? 'Non classé' }}
            </span>
        </div>

        <div>
            <span class="prix">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix_actuel, 2) }}</span>
            @if($oeuvre->prix_promo)
                <span class="prix-ancien">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix, 2) }}</span>
                <span style="font-size:14px;color:#2ECC71;margin-left:8px;">
                    -{{ round((1 - $oeuvre->prix_promo/$oeuvre->prix)*100) }}%
                </span>
            @endif
        </div>

        <div class="stock">
            @if($oeuvre->stock > 0)
                <span class="ok"><i class="fas fa-check-circle"></i> {{ $oeuvre->stock }} en stock</span>
            @else
                <span class="out"><i class="fas fa-times-circle"></i> Rupture de stock</span>
            @endif
        </div>

        <div class="description">
            {{ $oeuvre->description }}
        </div>

        <div class="caracteristiques">
            @if($oeuvre->materiaux)
                <div class="item">
                    <div class="label">Matériaux</div>
                    <div class="value">{{ $oeuvre->materiaux }}</div>
                </div>
            @endif
            @if($oeuvre->dimensions)
                <div class="item">
                    <div class="label">Dimensions</div>
                    <div class="value">{{ $oeuvre->dimensions }}</div>
                </div>
            @endif
            @if($oeuvre->published_at)
                <div class="item">
                    <div class="label">Publiée le</div>
                    <div class="value">{{ \Carbon\Carbon::parse($oeuvre->published_at)->locale('fr')->isoFormat('DD MMM YYYY') }}</div>
                </div>
            @endif
            <div class="item">
                <div class="label">Référence</div>
                <div class="value">#{{ $oeuvre->id }}</div>
            </div>
        </div>

        <div class="actions">
            @if($oeuvre->stock > 0)
                <form action="{{ route('client.panier.add') }}" method="POST" style="flex:1;display:flex;">
                    @csrf
                    <input type="hidden" name="oeuvre_id" value="{{ $oeuvre->id }}">
                    <input type="hidden" name="quantite" value="1">
                    <button type="submit" class="btn-add">
                        <i class="fas fa-cart-plus"></i> Ajouter au panier
                    </button>
                </form>
            @else
                <button class="btn-add" disabled>
                    <i class="fas fa-times"></i> Indisponible
                </button>
            @endif

            <button class="btn-favorite {{ $estFavori ? 'active' : '' }}"
                    onclick="toggleFavori({{ $oeuvre->id }})"
                    title="Ajouter aux favoris">
                <i class="fas fa-heart"></i>
            </button>
        </div>
    </div>
</div>

<!-- ===== AVIS ===== -->
<div class="note-section">
    <div class="header">
        <div>
            <span class="moyenne">{{ $stats['moyenne_notes'] }} <span>/ 5</span></span>
            <span class="etoiles">
                @php
                    $moyenne = $stats['moyenne_notes'];
                    $fullStars = floor($moyenne);
                    $halfStar = $moyenne - $fullStars >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - $fullStars - $halfStar;
                @endphp
                @for($i = 0; $i < $fullStars; $i++) ★ @endfor
                @if($halfStar) ☆ @endif
                @for($i = 0; $i < $emptyStars; $i++) ☆ @endfor
            </span>
        </div>
        <div style="font-size:14px;color:#95A5A6;">
            {{ $stats['total_avis'] }} avis
        </div>
    </div>

    @if($stats['total_avis'] > 0)
        <div class="distribution">
            @foreach([5,4,3,2,1] as $note)
                @php
                    $count = $stats['repartition_notes'][$note];
                    $max = max($stats['repartition_notes']) > 0 ? max($stats['repartition_notes']) : 1;
                    $percentage = ($count / $max) * 100;
                @endphp
                <div class="bar-item">
                    <span class="label">{{ $note }} ★</span>
                    <div class="bar">
                        <div class="fill" style="width: {{ $percentage }}%;"></div>
                    </div>
                    <span class="count">{{ $count }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- ===== LISTE AVIS ===== -->
@if($oeuvre->avis->count() > 0)
    <h3 style="font-size:18px;font-weight:600;color:#2D3436;margin-bottom:12px;">
        <i class="fas fa-comments"></i> Avis des clients
    </h3>

    @foreach($oeuvre->avis as $avis)
        <div class="avis-item">
            <div class="header">
                <span class="client">{{ $avis->user->name ?? 'Client' }}</span>
                <span class="note">{{ str_repeat('★', $avis->note) }}{{ str_repeat('☆', 5 - $avis->note) }}</span>
                <span class="date">{{ \Carbon\Carbon::parse($avis->created_at)->locale('fr')->diffForHumans() }}</span>
            </div>
            <div class="commentaire">{{ $avis->commentaire ?? 'Aucun commentaire' }}</div>
            @if($avis->reponse)
                <div style="margin-top:8px;padding:8px 12px;background:#F8F9FA;border-radius:8px;border-left:3px solid #2ECC71;">
                    <div style="font-size:11px;font-weight:600;color:#2ECC71;text-transform:uppercase;">Réponse de l'artisan</div>
                    <div style="font-size:14px;color:#2D3436;">{{ $avis->reponse }}</div>
                </div>
            @endif
        </div>
    @endforeach
@endif

<!-- ===== SIMILAIRES ===== -->
@if($similaires->count() > 0)
    <h3 style="font-size:18px;font-weight:600;color:#2D3436;margin-top:32px;margin-bottom:12px;">
        <i class="fas fa-share-alt"></i> Œuvres similaires
    </h3>

    <div class="similaires-grid">
        @foreach($similaires as $similaire)
            <a href="{{ route('client.oeuvre.show', $similaire->slug) }}" class="similaire-card">
                <img src="{{ $similaire->image_principale ?? asset('images/placeholder.jpg') }}"
                     alt="{{ $similaire->titre }}" loading="lazy">
                <div class="body">
                    <div class="titre">{{ $similaire->titre }}</div>
                    <div class="prix">{{ $similaire->devise->symbole ?? 'FC' }} {{ number_format($similaire->prix_actuel, 0) }}</div>
                </div>
            </a>
        @endforeach
    </div>
@endif

<script>
    // ===== CHANGER IMAGE PRINCIPALE =====
    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnails img').forEach(img => img.classList.remove('active'));
        element.classList.add('active');
    }

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
                const btn = document.querySelector('.btn-favorite');
                if (btn) {
                    btn.classList.toggle('active');
                }
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
</script>

@endsection