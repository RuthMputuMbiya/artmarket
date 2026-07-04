@extends('layouts.client')

@section('title', 'Paiement confirme - ArtMarket')

@section('content')

<style>
    .confirmation-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #D4F4E6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .confirmation-icon i {
        font-size: 40px;
        color: #2ECC71;
    }

    .confirmation-card {
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        max-width: 600px;
        margin: 0 auto;
    }

    .confirmation-card h2 {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 8px;
    }

    .confirmation-card .reference {
        font-size: 16px;
        color: #6C3CE1;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .confirmation-card .message {
        color: #95A5A6;
        margin-bottom: 24px;
        line-height: 1.7;
    }

    .confirmation-card .details {
        background: #F8F9FA;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        text-align: left;
    }

    .confirmation-card .details .row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #F0F0F0;
    }

    .confirmation-card .details .row:last-child {
        border-bottom: none;
    }

    .confirmation-card .details .row .label {
        color: #95A5A6;
    }

    .confirmation-card .details .row .value {
        font-weight: 600;
        color: #2D3436;
    }

    .confirmation-card .details .row .value.phone {
        color: #6C3CE1;
        font-weight: 700;
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.paye { background: #D4F4E6; color: #2ECC71; }
    .badge-status.reussi { background: #D4F4E6; color: #2ECC71; }

    .confirmation-card .actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
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

    @media (max-width: 480px) {
        .confirmation-card {
            padding: 20px;
        }
        .confirmation-card .details .row {
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        .confirmation-card .actions {
            flex-direction: column;
            align-items: center;
        }
        .confirmation-card .actions .btn-primary,
        .confirmation-card .actions .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="confirmation-card">
    <div class="confirmation-icon">
        <i class="fas fa-check-circle"></i>
    </div>

    <h2>Paiement confirme</h2>
    <div class="reference">Reference: {{ $paiement->reference ?? 'N/A' }}</div>

    <p class="message">
        Votre paiement a ete effectue avec succes.
        Vous recevrez une confirmation par email.
    </p>

    <div class="details">
        <div class="row">
            <span class="label">Commande</span>
            <span class="value">#{{ $commande->reference }}</span>
        </div>
        <div class="row">
            <span class="label">Montant paye</span>
            <span class="value" style="font-size:18px;color:#6C3CE1;">
                {{ $commande->devise->symbole ?? 'FC' }} {{ number_format($paiement->montant ?? $commande->total_ttc, 2) }}
            </span>
        </div>
        <div class="row">
            <span class="label">Methode</span>
            <span class="value">
                @switch($paiement->methode ?? 'shwary')
                    @case('orange_money') Orange Money @break
                    @case('airtel_money') Airtel Money @break
                    @case('mpesa') M-Pesa @break
                    @case('carte') Carte bancaire @break
                    @default {{ ucfirst($paiement->methode ?? 'Shwary') }}
                @endswitch
            </span>
        </div>
        <div class="row">
            <span class="label">Telephone</span>
            <span class="value phone">
                @if($paiement->phone)
                    <i class="fas fa-phone" style="color:#6C3CE1;"></i> +243 {{ $paiement->phone }}
                @else
                    Non renseigne
                @endif
            </span>
        </div>
        <div class="row">
            <span class="label">Statut</span>
            <span class="value">
                <span class="badge-status paye">Paye</span>
            </span>
        </div>
        <div class="row">
            <span class="label">Date</span>
            <span class="value">{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}</span>
        </div>
    </div>

    <div class="actions">
        <a href="{{ route('client.commandes.show', $commande->id) }}" class="btn-primary">
            <i class="fas fa-eye"></i> Suivre ma commande
        </a>
        <a href="{{ route('client.catalogue') }}" class="btn-secondary">
            <i class="fas fa-compass"></i> Continuer mes achats
        </a>
    </div>
</div>

@endsection