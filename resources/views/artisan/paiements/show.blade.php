@extends('layouts.artisan')

@section('title', 'Détails du paiement')

@section('page-title', 'Détails du paiement')

@section('content')

<style>
    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: white;
        border-radius: 10px;
        margin-bottom: 24px;
        border: 1px solid #eef0f2;
        flex-wrap: wrap;
        gap: 12px;
    }

    .payment-header .reference {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a2e;
    }

    .payment-header .sub {
        font-size: 13px;
        color: #6b7280;
    }

    .badge-statut {
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 700;
    }

    .badge-statut.en_attente { background: #fef3c7; color: #d97706; }
    .badge-statut.paye { background: #d1fae5; color: #059669; }
    .badge-statut.echoue { background: #fee2e2; color: #dc2626; }
    .badge-statut.rembourse { background: #f3f4f6; color: #6b7280; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .info-item {
        background: #f9fafb;
        padding: 12px 16px;
        border-radius: 8px;
    }

    .info-item .label {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .info-item .value {
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
        margin-top: 2px;
    }

    .info-item .value .highlight {
        font-size: 20px;
        font-weight: 700;
        color: #4f46e5;
    }

    .card-section {
        background: white;
        border-radius: 10px;
        border: 1px solid #eef0f2;
        padding: 20px;
        margin-bottom: 24px;
    }

    .card-section .title {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 16px;
    }

    .commande-info {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }

    .commande-info .item {
        flex: 1;
        min-width: 180px;
        background: #f9fafb;
        padding: 12px 16px;
        border-radius: 8px;
    }

    .commande-info .item .label {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .commande-info .item .value {
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
        margin-top: 2px;
    }

    .badge-commande {
        padding: 3px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-commande.en_attente { background: #fef3c7; color: #d97706; }
    .badge-commande.confirmee { background: #dbeafe; color: #2563eb; }
    .badge-commande.livree { background: #d1fae5; color: #059669; }
    .badge-commande.annulee { background: #fee2e2; color: #dc2626; }

    .btn-back {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1.5px solid #e5e7eb;
        background: #f3f4f6;
        color: #374151;
        text-decoration: none;
        font-family: inherit;
        min-height: 40px;
    }

    .btn-back:hover {
        background: #e5e7eb;
        color: #1f2937;
    }

    .btn-recu {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        background: #059669;
        color: white;
        text-decoration: none;
        font-family: inherit;
        min-height: 40px;
    }

    .btn-recu:hover {
        background: #047857;
        color: white;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .payment-header {
            flex-direction: column;
            text-align: center;
        }

        .commande-info {
            flex-direction: column;
        }

        .commande-info .item {
            min-width: 100%;
        }
    }
</style>

<!-- ===== HEADER ===== -->
<div class="payment-header">
    <div>
        <div class="reference">{{ $paiement->reference }}</div>
        <div class="sub">Commande #{{ $paiement->commande->reference ?? 'N/A' }}</div>
    </div>
    <div>
        <span class="badge-statut {{ $paiement->statut }}">
            {{ ucfirst(str_replace('_', ' ', $paiement->statut)) }}
        </span>
    </div>
</div>

<!-- ===== INFOS PAIEMENT ===== -->
<div class="card-section">
    <div class="title">Informations du paiement</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="label">Montant</div>
            <div class="value">
                <span class="highlight">{{ $paiement->devise->symbole ?? 'FC' }} {{ number_format($paiement->montant, 2) }}</span>
            </div>
        </div>
        <div class="info-item">
            <div class="label">Méthode</div>
            <div class="value">{{ ucfirst(str_replace('_', ' ', $paiement->methode)) }}</div>
        </div>
        <div class="info-item">
            <div class="label">Client</div>
            <div class="value">{{ $paiement->commande->user->name ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Date du paiement</div>
            <div class="value">{{ \Carbon\Carbon::parse($paiement->date_paiement ?? $paiement->created_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Transaction ID</div>
            <div class="value">{{ $paiement->transaction_id ?? 'Non disponible' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Devise</div>
            <div class="value">{{ $paiement->devise->nom ?? 'Non définie' }} ({{ $paiement->devise->code ?? 'N/A' }})</div>
        </div>
        @if($paiement->montant_cdf || $paiement->montant_usd)
            <div class="info-item" style="grid-column:1/-1;">
                <div class="label">Équivalence</div>
                <div class="value">
                    @if($paiement->montant_cdf)
                        <span style="margin-right:16px;">CDF: {{ number_format($paiement->montant_cdf, 2) }}</span>
                    @endif
                    @if($paiement->montant_usd)
                        <span>USD: {{ number_format($paiement->montant_usd, 2) }}</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- ===== INFOS COMMANDE ===== -->
<div class="card-section">
    <div class="title">Commande associée</div>
    <div class="commande-info">
        <div class="item">
            <div class="label">Référence</div>
            <div class="value">#{{ $paiement->commande->reference ?? 'N/A' }}</div>
        </div>
        <div class="item">
            <div class="label">Total commande</div>
            <div class="value">{{ $paiement->devise->symbole ?? 'FC' }} {{ number_format($paiement->commande->total_ttc ?? 0, 2) }}</div>
        </div>
        <div class="item">
            <div class="label">Statut</div>
            <div class="value">
                <span class="badge-commande {{ $paiement->commande->statut ?? 'en_attente' }}">
                    {{ ucfirst($paiement->commande->statut ?? 'N/A') }}
                </span>
            </div>
        </div>
        <div class="item">
            <div class="label">Date commande</div>
            <div class="value">{{ \Carbon\Carbon::parse($paiement->commande->created_at)->locale('fr')->isoFormat('DD MMM YYYY') }}</div>
        </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('artisan.commandes.show', $paiement->commande->id) }}" class="btn-back">Voir la commande</a>
        @if($paiement->statut === 'paye')
            <a href="{{ route('artisan.paiements.recu', $paiement->id) }}" class="btn-recu" target="_blank">Télécharger le reçu</a>
        @endif
    </div>
</div>

<!-- ===== RETOUR ===== -->
<div style="margin-top:20px;">
    <a href="{{ route('artisan.paiements.index') }}" class="btn-back">← Retour à la liste</a>
</div>

@endsection