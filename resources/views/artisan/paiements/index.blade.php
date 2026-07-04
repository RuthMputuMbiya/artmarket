@extends('layouts.artisan')

@section('title', 'Mes paiements')

@section('page-title', 'Paiements')

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 3px solid #6C3CE1;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .stat-card .number {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-card .label {
        font-size: 12px;
        color: #95A5A6;
        margin-top: 2px;
    }

    .stat-card .icon {
        font-size: 24px;
        display: block;
        margin-bottom: 4px;
    }

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

    .filters select,
    .filters input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
    }

    .filters select:focus,
    .filters input:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.1);
    }

    .filters .btn-primary {
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

    .filters .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
        color: white;
    }

    .filters .btn-secondary {
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

    .filters .btn-secondary:hover {
        background: #E8ECF1;
        transform: translateY(-2px);
    }

    .filters .btn-export {
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

    .filters .btn-export:hover {
        background: #1a1a1a;
        transform: translateY(-2px);
        color: white;
    }

    .table-container {
        overflow-x: auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 700px;
    }

    .table-custom thead {
        background: #F8F9FA;
    }

    .table-custom th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: #2D3436;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #E8ECF1;
    }

    .table-custom td {
        padding: 14px 16px;
        border-bottom: 1px solid #F0F0F0;
        vertical-align: middle;
    }

    .table-custom tr:hover {
        background: #F8F9FA;
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.en_attente { background: #FDE8D0; color: #F39C12; }
    .badge-status.paye { background: #D4F4E6; color: #2ECC71; }
    .badge-status.echoue { background: #FCD4D4; color: #E74C3C; }
    .badge-status.rembourse { background: #F0F0F0; color: #7F8C8D; }

    .action-btn {
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

    .action-btn.action-view { background: #E8E0F8; color: #6C3CE1; }
    .action-btn.action-recu { background: #D4F4E6; color: #2ECC71; }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-btn.action-view:hover { background: #6C3CE1; color: white; }
    .action-btn.action-recu:hover { background: #2ECC71; color: white; }

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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95A5A6;
    }

    .empty-state .icon {
        font-size: 48px;
        display: block;
        margin-bottom: 16px;
        color: #D4D4D4;
    }

    .empty-state .title {
        font-size: 18px;
        color: #2D3436;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .stat-card {
            padding: 12px;
        }

        .stat-card .number {
            font-size: 18px;
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

        .table-custom {
            font-size: 12px;
            min-width: 600px;
        }

        .table-custom th,
        .table-custom td {
            padding: 10px 12px;
        }

        .pagination-info {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .stat-card .number {
            font-size: 16px;
        }

        .stat-card .label {
            font-size: 10px;
        }

        .action-btn {
            font-size: 10px;
            padding: 4px 8px;
        }
    }
</style>

<!-- ===== STATISTIQUES ===== -->
<div class="stats-grid">
    <div class="stat-card" style="border-left-color: #6C3CE1;">
        <span class="icon"></span>
        <div class="number">{{ $stats['total_paiements'] }}</div>
        <div class="label">Total paiements</div>
    </div>
    <div class="stat-card" style="border-left-color: #2ECC71;">
        <span class="icon"></span>
        <div class="number">{{ $stats['total_payes'] }}</div>
        <div class="label">Payes</div>
    </div>
    <div class="stat-card" style="border-left-color: #F39C12;">
        <span class="icon"></span>
        <div class="number">{{ $stats['total_attente'] }}</div>
        <div class="label">En attente</div>
    </div>
    <div class="stat-card" style="border-left-color: #E74C3C;">
        <span class="icon"></span>
        <div class="number">{{ $stats['total_echoue'] }}</div>
        <div class="label">Echoues</div>
    </div>
    <div class="stat-card" style="border-left-color: #9B59B6;">
        <span class="icon">{{ $commande->devise->symbole ?? 'FC' }}</span>
        <div class="number">{{ number_format($stats['total_paye'], 0) }}</div>
        <div class="label">Total encaisse</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
<div class="filters">
    <form action="{{ route('artisan.paiements.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
        <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
               style="flex:1;min-width:150px;">

        <select name="statut">
            <option value="">Tous les statuts</option>
            @foreach($statuts as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                    {{ ucfirst($statut) }}
                </option>
            @endforeach
        </select>

        <select name="methode">
            <option value="">Toutes les methodes</option>
            @foreach($methodes as $methode)
                <option value="{{ $methode }}" {{ request('methode') == $methode ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $methode)) }}
                </option>
            @endforeach
        </select>

        <input type="date" name="date_debut" value="{{ request('date_debut') }}" style="max-width:140px;">
        <span style="color:#95A5A6;">a</span>
        <input type="date" name="date_fin" value="{{ request('date_fin') }}" style="max-width:140px;">

        <button type="submit" class="btn-primary">Filtrer</button>
        <a href="{{ route('artisan.paiements.index') }}" class="btn-secondary">Reinitialiser</a>
    </form>

    
</div>

<!-- ===== TABLE ===== -->
<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Commande</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Methode</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paiements as $paiement)
                <tr>
                    <td>
                        <strong>{{ $paiement->reference }}</strong>
                    </td>
                    <td>
                        #{{ $paiement->commande->reference ?? 'N/A' }}
                    </td>
                    <td>
                        {{ $paiement->commande->user->name ?? 'N/A' }}
                    </td>
                    <td>
                        <strong>{{ $paiement->commande->devise->symbole ?? 'FC' }} {{ number_format($paiement->montant, 2) }}</strong>
                    </td>
                    <td>
                        @switch($paiement->methode)
                            @case('orange_money') Orange Money @break
                            @case('airtel_money') Airtel Money @break
                            @case('mpesa') M-Pesa @break
                            @case('carte') Carte bancaire @break
                            @default {{ ucfirst($paiement->methode ?? 'N/A') }}
                        @endswitch
                    </td>
                    <td>
                        <span class="badge-status {{ $paiement->statut }}">
                            {{ ucfirst($paiement->statut) }}
                        </span>
                    </td>
                    <td style="font-size:13px;color:#95A5A6;">
                        {{ $paiement->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}
                        <div style="font-size:11px;">{{ $paiement->created_at->locale('fr')->isoFormat('HH:mm') }}</div>
                    </td>
                    <td>
                        <div class="actions" style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap;">
                            <a href="{{ route('artisan.paiements.show', $paiement->id) }}" class="action-btn action-view">Voir</a>
                            @if($paiement->statut === 'paye')
                                <a href="{{ route('artisan.paiements.recu', $paiement->id) }}" class="action-btn action-recu" target="_blank">Recu</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <span class="icon">C</span>
                            <div class="title">Aucun paiement trouve</div>
                            <p>Aucun paiement ne correspond a vos criteres</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ===== PAGINATION ===== -->
<div class="pagination-info">
    <div class="info">
        Affichage de {{ $paiements->firstItem() ?? 0 }} a {{ $paiements->lastItem() ?? 0 }} sur {{ $paiements->total() }} paiements
    </div>
    <div>
        {{ $paiements->appends(request()->query())->links() }}
    </div>
</div>

@endsection