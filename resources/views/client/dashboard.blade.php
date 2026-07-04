@extends('layouts.client')

@section('title', 'Mon Espace - ArtMarket')

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        border-left: 4px solid #6C3CE1;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .stat-card .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-card .stat-label {
        font-size: 13px;
        color: #95A5A6;
    }

    .stat-card .stat-icon {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
    }

    .card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }

    .card h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 16px;
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.en_attente { background: #FDE8D0; color: #F39C12; }
    .badge-status.en_cours { background: #D4E8FC; color: #3498DB; }
    .badge-status.expediee { background: #D4F4E6; color: #2ECC71; }
    .badge-status.livree { background: #D4F4E6; color: #27AE60; }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .table-container {
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .table-custom thead {
        background: #F8F9FA;
    }

    .table-custom th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #2D3436;
        border-bottom: 2px solid #E8ECF1;
    }

    .table-custom td {
        padding: 12px 16px;
        border-bottom: 1px solid #E8ECF1;
        color: #2D3436;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: #95A5A6;
    }

    .empty-state .icon {
        font-size: 40px;
        display: block;
        margin-bottom: 12px;
        color: #D4D4D4;
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<!-- ===== STATS ===== -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-icon">T</span>
        <div class="stat-number">{{ $stats['total_commandes'] ?? 0 }}</div>
        <div class="stat-label">Total commandes</div>
    </div>
    <div class="stat-card" style="border-color:#F39C12;">
        <span class="stat-icon">A</span>
        <div class="stat-number">{{ $stats['commandes_en_cours'] ?? 0 }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card" style="border-color:#2ECC71;">
        <span class="stat-icon">L</span>
        <div class="stat-number">{{ $stats['commandes_livrees'] ?? 0 }}</div>
        <div class="stat-label">Livrees</div>
    </div>
    <div class="stat-card" style="border-color:#6C3CE1;">
        <span class="stat-icon">$</span>
        <div class="stat-number">{{ number_format($stats['total_depenses'] ?? 0, 0) }} FC</div>
        <div class="stat-label">Depenses totales</div>
    </div>
</div>

<!-- ===== COMMANDES EN ATTENTE ===== -->
@if(isset($commandes_attente) && $commandes_attente->count() > 0)
    <div class="card">
        <h3>Commandes en attente de paiement</h3>
        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commandes_attente as $commande)
                        <tr>
                            <td>#{{ $commande->reference }}</td>
                            <td>{{ number_format($commande->total_ttc, 2) }} FC</td>
                            <td>{{ \Carbon\Carbon::parse($commande->created_at)->locale('fr')->isoFormat('DD MMM YYYY') }}</td>
                            <td>
                                <a href="{{ route('client.paiement.checkout', $commande->id) }}" class="btn-primary">
                                    <i class="fas fa-credit-card"></i> Payer
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- ===== DERNIERES COMMANDES ===== -->
<div class="grid-2">
    <div class="card">
        <h3>Dernieres commandes</h3>
        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Ref.</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieres_commandes ?? [] as $commande)
                        <tr>
                            <td>#{{ $commande->reference }}</td>
                            <td>{{ number_format($commande->total_ttc, 2) }} FC</td>
                            <td><span class="badge-status {{ $commande->statut }}">{{ ucfirst($commande->statut) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <span class="icon">C</span>
                                    <p>Aucune commande</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== DEPENSES PAR MOIS ===== -->
    <div class="card">
        <h3>Depenses par mois</h3>
        @if(isset($depenses_par_mois) && count($depenses_par_mois) > 0)
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach($depenses_par_mois as $mois)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 12px;background:#F8F9FA;border-radius:8px;">
                        <span style="font-weight:600;color:#2D3436;">{{ $mois['mois'] }}</span>
                        <span style="font-weight:600;color:#6C3CE1;">{{ number_format($mois['total'], 0) }} FC</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <span class="icon">$</span>
                <p>Aucune depense</p>
            </div>
        @endif
    </div>
</div>

@endsection