@extends('layouts.artisan')

@section('title', 'Details de la commande')

@section('page-title', 'Details de la commande #' . $commande->reference)

@section('content')

<style>
    .status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: white;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        flex-wrap: wrap;
        gap: 12px;
    }

    .status-bar .statut-actuel {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .status-bar .statut-actuel .label {
        font-size: 13px;
        color: #95A5A6;
    }

    .status-bar .statut-actuel .badge {
        font-size: 16px;
        font-weight: 700;
        padding: 6px 20px;
        border-radius: 50px;
    }

    .badge.en_attente { background: #FDE8D0; color: #F39C12; }
    .badge.en_cours { background: #D4E8FC; color: #3498DB; }
    .badge.expediee { background: #D4E8FC; color: #2980B9; }
    .badge.livree { background: #D4F4E6; color: #2ECC71; }
    .badge.annulee { background: #FCD4D4; color: #E74C3C; }
    .badge.rembourse { background: #F0F0F0; color: #7F8C8D; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .info-item {
        background: #F8F9FA;
        padding: 12px 16px;
        border-radius: 10px;
    }

    .info-item .label {
        font-size: 11px;
        font-weight: 600;
        color: #95A5A6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item .value {
        font-size: 14px;
        font-weight: 500;
        color: #2D3436;
        margin-top: 2px;
    }

    .product-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 0;
        border-bottom: 1px solid #F0F0F0;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-item img {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #E8ECF1;
    }

    .product-item .info {
        flex: 1;
    }

    .product-item .info .titre {
        font-weight: 600;
        color: #2D3436;
    }

    .product-item .info .artisan {
        font-size: 12px;
        color: #95A5A6;
    }

    .product-item .prix {
        text-align: right;
    }

    .product-item .prix .total {
        font-weight: 700;
        color: #6C3CE1;
    }

    .product-item .prix .unitaire {
        font-size: 12px;
        color: #95A5A6;
    }

    .totaux {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid #F0F0F0;
        text-align: right;
    }

    .totaux .ligne {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        padding: 4px 0;
    }

    .totaux .ligne .label {
        color: #95A5A6;
    }

    .totaux .ligne .value {
        font-weight: 600;
        min-width: 120px;
        text-align: right;
    }

    .totaux .ligne.total .value {
        font-size: 20px;
        color: #6C3CE1;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
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

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .status-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .product-item {
            flex-wrap: wrap;
        }

        .product-item .prix {
            text-align: left;
            width: 100%;
            padding-left: 76px;
        }

        .totaux .ligne {
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
        }
    }
</style>

<div class="status-bar">
    <div class="statut-actuel">
        <span class="label">Statut actuel :</span>
        <span class="badge {{ $commande->statut }}">{{ ucfirst($commande->statut) }}</span>
    </div>

    <form action="{{ route('artisan.commandes.update-status', $commande->id) }}" method="POST" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        @csrf
        <select name="statut" style="padding:8px 14px;border-radius:10px;border:2px solid #E8ECF1;font-size:13px;font-family:'Poppins',sans-serif;outline:none;">
            <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
            <option value="en_cours" {{ $commande->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
            <option value="expediee" {{ $commande->statut == 'expediee' ? 'selected' : '' }}>Expediee</option>
            <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}>Livree</option>
            <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulee</option>
            <option value="rembourse" {{ $commande->statut == 'rembourse' ? 'selected' : '' }}>Remboursee</option>
        </select>
        <button type="submit" class="btn-primary">Mettre a jour</button>
    </form>
</div>

<div class="card">
    <div class="card-title">Informations client</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="label">Nom</div>
            <div class="value">{{ $commande->user->name ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Email</div>
            <div class="value">{{ $commande->user->email ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Telephone</div>
            <div class="value">{{ $commande->user->telephone ?? 'Non renseigne' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Date de commande</div>
            <div class="value">{{ $commande->created_at->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-title">Produits commandes</div>

    @foreach($commande->lignes as $ligne)
        <div class="product-item">
            <img src="{{ $ligne->oeuvre->image_principale ?? asset('images/placeholder.jpg') }}"
                 alt="{{ $ligne->oeuvre->titre }}">

            <div class="info">
                <div class="titre">{{ $ligne->oeuvre->titre }}</div>
                <div class="artisan">
                    Quantite: {{ $ligne->quantite }}
                </div>
            </div>

            <div class="prix">
                <div class="total">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($ligne->total, 2) }}</div>
                <div class="unitaire">{{ number_format($ligne->prix_unitaire, 2) }} x {{ $ligne->quantite }}</div>
            </div>
        </div>
    @endforeach

    <div class="totaux">
        <div class="ligne">
            <span class="label">Sous-total</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ht, 2) }}</span>
        </div>
        <div class="ligne">
            <span class="label">Commission</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->commission, 2) }}</span>
        </div>
        <div class="ligne total">
            <span class="label" style="font-weight:700;">Total TTC</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</span>
        </div>
    </div>
</div>

@if($commande->paiements->count() > 0)
    <div class="card">
        <div class="card-title">Paiements</div>
        @foreach($commande->paiements as $paiement)
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F0F0F0;">
                <div>
                    <span style="font-weight:500;">{{ $paiement->reference }}</span>
                    <span style="margin-left:12px;font-size:13px;color:#95A5A6;">{{ ucfirst($paiement->methode) }}</span>
                </div>
                <div>
                    <span style="font-weight:600;">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($paiement->montant, 2) }}</span>
                    <span class="badge-status {{ $paiement->statut }}" style="margin-left:12px;">
                        {{ ucfirst($paiement->statut) }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
@endif

<div style="display:flex;gap:12px;margin-top:20px;">
    <a href="{{ route('artisan.commandes.index') }}" class="btn-secondary">
        Retour a la liste
    </a>
</div>

@endsection