@extends('layouts.admin')

@section('title', 'Tableau de bord - Admin')

@section('page-title', 'Tableau de bord')

@section('content')

<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border-left: 4px solid var(--admin-primary);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .stat-card .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .stat-card .stat-icon.purple { background: #E8E0F8; color: #6C3CE1; }
    .stat-card .stat-icon.orange { background: #FDE8D0; color: #F39C12; }
    .stat-card .stat-icon.green { background: #D4F4E6; color: #2ECC71; }
    .stat-card .stat-icon.red { background: #FCD4D4; color: #E74C3C; }
    .stat-card .stat-icon.blue { background: #D4E8FC; color: #3498DB; }
    .stat-card .stat-icon.pink { background: #FCE4EC; color: #E91E63; }
    .stat-card .stat-icon.teal { background: #E0F2F1; color: #00897B; }

    .stat-card .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-card .stat-label {
        font-size: 13px;
        color: #95A5A6;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .chart-container h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 16px;
    }

    .bar-chart {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 200px;
        gap: 8px;
        padding-top: 16px;
    }

    .bar-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .bar {
        width: 100%;
        max-width: 40px;
        background: linear-gradient(180deg, #6C3CE1, #8B6CE6);
        border-radius: 4px 4px 0 0;
        min-height: 4px;
        transition: height 0.6s ease;
    }

    .bar-label {
        font-size: 11px;
        color: #95A5A6;
        margin-top: 6px;
    }

    .bar-value {
        font-size: 10px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 4px;
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

    .table-custom tbody tr:hover {
        background: rgba(108, 60, 225, 0.03);
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
    .badge-status.annulee { background: #FCD4D4; color: #E74C3C; }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }

        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .stat-card {
            padding: 16px;
        }

        .stat-card .stat-number {
            font-size: 20px;
        }
    }
</style>

<!-- ===== STATS ===== -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div class="stat-number">{{ $stats['total_users'] ?? 0 }}</div>
        <div class="stat-label">Utilisateurs</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-store"></i></div>
        <div class="stat-number">{{ $stats['total_artisans'] ?? 0 }}</div>
        <div class="stat-label">Artisans</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-palette"></i></div>
        <div class="stat-number">{{ $stats['total_oeuvres'] ?? 0 }}</div>
        <div class="stat-label">Oeuvres</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-credit-card"></i></div>
        <div class="stat-number">{{ number_format($stats['total_ventes'] ?? 0, 0) }} FC</div>
        <div class="stat-label">Chiffre d'affaires</div>
    </div>
    
</div>

<!-- ===== GRAPHIQUES ===== -->
<div class="grid-2">
    <div class="chart-container">
        <h3>Ventes des 7 derniers jours</h3>
        <div class="bar-chart">
            @foreach($ventes_7_jours ?? [] as $vente)
                <div class="bar-item">
                    <div class="bar-value">{{ number_format($vente['total'] ?? 0, 0) }}</div>
                    <div class="bar" style="height: {{ ($vente['total'] ?? 0) > 0 ? (($vente['total'] ?? 0) / max(array_column($ventes_7_jours ?? [], 'total') ?: [1]) * 180) : 4 }}px;"></div>
                    <div class="bar-label">{{ $vente['date'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="chart-container">
        <h3>Ventes par mois</h3>
        <div class="bar-chart">
            @foreach($ventes_par_mois ?? [] as $vente)
                <div class="bar-item">
                    <div class="bar-value">{{ number_format($vente['total'] ?? 0, 0) }}</div>
                    <div class="bar" style="height: {{ ($vente['total'] ?? 0) > 0 ? (($vente['total'] ?? 0) / max(array_column($ventes_par_mois ?? [], 'total') ?: [1]) * 180) : 4 }}px; background: linear-gradient(180deg, #FF6B6B, #FF9F43);"></div>
                    <div class="bar-label">{{ $vente['mois'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>



<!-- ===== DERNIERS UTILISATEURS ===== -->
<div class="chart-container">
    <h3>Derniers utilisateurs</h3>
    <div class="table-container">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                @forelse($derniers_utilisateurs ?? [] as $user)
                    <tr>
                        <td><strong>{{ $user->name ?? '' }}</strong></td>
                        <td>{{ $user->email ?? '' }}</td>
                        <td><span class="badge-status" style="background:#E8E0F8;color:#6C3CE1;">{{ $user->role->nom ?? 'Client' }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at ?? now())->locale('fr')->isoFormat('DD MMM YYYY') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:#95A5A6;">Aucun utilisateur</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection