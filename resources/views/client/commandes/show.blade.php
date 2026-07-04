@extends('layouts.client')

@section('title', 'Détails de la commande #' . $commande->reference)

@section('content')

<style>
    .commande-detail {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .commande-detail .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #F0F0F0;
    }

    .commande-detail .header .reference {
        font-size: 22px;
        font-weight: 700;
        color: #2D3436;
    }

    .commande-detail .header .reference small {
        font-weight: 400;
        font-size: 14px;
        color: #95A5A6;
        margin-left: 8px;
    }

    .commande-detail .header .total {
        font-size: 28px;
        font-weight: 800;
        color: #6C3CE1;
    }

    .badge-status {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.en_attente { background: #FDE8D0; color: #F39C12; }
    .badge-status.en_cours { background: #D4E8FC; color: #3498DB; }
    .badge-status.expediee { background: #D4E8FC; color: #2980B9; }
    .badge-status.livree { background: #D4F4E6; color: #2ECC71; }
    .badge-status.annulee { background: #FCD4D4; color: #E74C3C; }
    .badge-status.rembourse { background: #F0F0F0; color: #7F8C8D; }

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

    .products-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    .products-table th {
        text-align: left;
        padding: 12px 8px;
        font-size: 12px;
        text-transform: uppercase;
        color: #95A5A6;
        border-bottom: 2px solid #F0F0F0;
    }

    .products-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #F0F0F0;
        vertical-align: middle;
    }

    .products-table .product-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .products-table .product-info img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        background: #F0F0F0;
    }

    .products-table .product-info .name {
        font-weight: 600;
        color: #2D3436;
    }

    .products-table .product-info .artisan {
        font-size: 12px;
        color: #95A5A6;
    }

    .total-row {
        font-weight: 700;
        font-size: 18px;
    }

    .total-row td {
        border-top: 2px solid #6C3CE1;
        padding-top: 16px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
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
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
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
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
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

    .btn-success {
        background: linear-gradient(135deg, #2ECC71, #27AE60);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(46,204,113,0.35);
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .commande-detail {
            padding: 16px;
        }

        .commande-detail .header {
            flex-direction: column;
            align-items: flex-start;
        }

        .commande-detail .header .total {
            font-size: 22px;
            align-self: flex-end;
        }

        .info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 12px;
        }

        .info-grid .item .value {
            font-size: 14px;
        }

        .products-table {
            font-size: 13px;
        }

        .products-table .product-info img {
            width: 40px;
            height: 40px;
        }

        .products-table th,
        .products-table td {
            padding: 8px 6px;
        }

        .actions {
            flex-direction: column;
        }

        .actions .btn-primary,
        .actions .btn-secondary,
        .actions .btn-danger,
        .actions .btn-success {
            width: 100%;
            justify-content: center;
            padding: 12px 20px;
        }
    }

    @media (max-width: 480px) {
        .commande-detail {
            padding: 12px;
        }

        .commande-detail .header .reference {
            font-size: 18px;
        }

        .commande-detail .header .reference small {
            font-size: 12px;
            display: block;
            margin-left: 0;
            margin-top: 4px;
        }

        .info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 10px;
        }

        .info-grid .item .label {
            font-size: 10px;
        }

        .info-grid .item .value {
            font-size: 13px;
        }

        .products-table {
            font-size: 12px;
        }

        .products-table .product-info {
            gap: 8px;
        }

        .products-table .product-info img {
            width: 32px;
            height: 32px;
        }

        .products-table th {
            font-size: 10px;
            padding: 6px 4px;
        }

        .products-table td {
            padding: 6px 4px;
        }

        .total-row {
            font-size: 15px;
        }

        .actions .btn-primary,
        .actions .btn-secondary,
        .actions .btn-danger,
        .actions .btn-success {
            font-size: 13px;
            padding: 10px 16px;
        }
    }
</style>

<div class="commande-detail">
    
    <!-- HEADER -->
    <div class="header">
        <div>
            <div class="reference">
                #{{ $commande->reference }}
                <small>Commandé le {{ $commande->created_at->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}</small>
            </div>
            <span class="badge-status {{ $commande->statut }}" style="margin-top:8px;">
                {{ ucfirst($commande->statut) }}
            </span>
        </div>
        <div class="total">
            {{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}
        </div>
    </div>

    <!-- INFOS -->
    <div class="info-grid">
        <div class="item">
            <span class="label">Statut</span>
            <span class="value">{{ ucfirst($commande->statut) }}</span>
        </div>
        <div class="item">
            <span class="label">Date de commande</span>
            <span class="value">{{ $commande->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}</span>
        </div>
        <div class="item">
            <span class="label">Total TTC</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</span>
        </div>
        <div class="item">
            <span class="label">Sous-total</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ht, 2) }}</span>
        </div>
        @if($commande->commission > 0)
        <div class="item">
            <span class="label">Commission (5%)</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->commission, 2) }}</span>
        </div>
        @endif
        {{-- ⚠️ LIGNE LIVRAISON SUPPRIMÉE --}}
        {{--
        <div class="item">
            <span class="label">Livraison</span>
            <span class="value" style="color:#2ECC71;">Gratuite</span>
        </div>
        --}}
        {{-- ⚠️ ADRESSE DE LIVRAISON SUPPRIMÉE --}}
        {{--
        <div class="item">
            <span class="label">Adresse de livraison</span>
            <span class="value">{{ $commande->adresse_livraison }}, {{ $commande->ville_livraison }}, {{ $commande->pays_livraison }}</span>
        </div>
        --}}
        @if($commande->notes)
        <div class="item">
            <span class="label">Notes</span>
            <span class="value">{{ $commande->notes }}</span>
        </div>
        @endif
    </div>

    <!-- PRODUITS -->
    <h3 style="font-size:18px;font-weight:700;color:#2D3436;margin-bottom:12px;">
        <i class="fas fa-box"></i> Articles commandés
    </h3>

    <div style="overflow-x:auto;">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Artisan</th>
                    <th style="text-align:center;">Qté</th>
                    <th style="text-align:right;">Prix unitaire</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->lignes as $ligne)
                <tr>
                    <td>
                        <div class="product-info">
                            @if($ligne->oeuvre->image_principale)
                                <img src="{{ $ligne->oeuvre->image_principale_url }}" alt="{{ $ligne->oeuvre->titre }}">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $ligne->oeuvre->titre }}">
                            @endif
                            <div>
                                <div class="name">{{ $ligne->oeuvre->titre }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="artisan">{{ $ligne->oeuvre->artisan->name ?? 'N/A' }}</div>
                    </td>
                    <td style="text-align:center;">{{ $ligne->quantite }}</td>
                    <td style="text-align:right;">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($ligne->prix_unitaire, 2) }}</td>
                    <td style="text-align:right;font-weight:600;">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($ligne->total, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" style="text-align:right;">Total</td>
                    <td style="text-align:right;color:#6C3CE1;">
                        {{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ACTIONS -->
    <div class="actions">
        <a href="{{ route('client.commandes.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>

        @if($peutPayer)
            <a href="{{ route('client.paiement.checkout', $commande->id) }}" class="btn-primary">
                <i class="fas fa-credit-card"></i> Payer maintenant
            </a>
        @endif

        @if($commande->statut === 'en_attente')
            <form action="{{ route('client.commande.annuler', $commande->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-danger" onclick="return confirm('Annuler cette commande ?')">
                    <i class="fas fa-times"></i> Annuler la commande
                </button>
            </form>
        @endif

        {{-- 📄 TÉLÉCHARGER LE REÇU --}}
        @if($commande->paiements()->where('statut', 'paye')->exists())
            <a href="{{ route('client.commande.recu', $commande->id) }}" class="btn-success" target="_blank">
                <i class="fas fa-file-pdf"></i> Télécharger le reçu
            </a>
        @endif
    </div>

</div>

@endsection