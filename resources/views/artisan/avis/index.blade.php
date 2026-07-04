@extends('layouts.artisan')

@section('title', 'Mes avis')

@section('page-title', 'Avis')

@section('content')

<style>
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 4px solid #6C3CE1;
        transition: all 0.3s ease;
    }

    .stat-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .stat-mini .number {
        font-size: 24px;
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

    .stat-mini.total { border-left-color: #6C3CE1; }
    .stat-mini.approved { border-left-color: #2ECC71; }
    .stat-mini.pending { border-left-color: #F39C12; }
    .stat-mini.moyenne { border-left-color: #3498DB; }

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
        min-width: 800px;
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

    .badge-status.approved { background: #D4F4E6; color: #2ECC71; }
    .badge-status.pending { background: #FDE8D0; color: #F39C12; }

    .stars {
        color: #F39C12;
        letter-spacing: 2px;
        font-size: 16px;
    }

    .btn-action {
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

    .btn-action.view { background: #E8E0F8; color: #6C3CE1; }
    .btn-action.respond { background: #D4E8FC; color: #3498DB; }
    .btn-action.delete { background: #FCD4D4; color: #E74C3C; }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.view:hover { background: #6C3CE1; color: white; }
    .btn-action.respond:hover { background: #3498DB; color: white; }
    .btn-action.delete:hover { background: #E74C3C; color: white; }

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

    .empty-state h3 {
        font-size: 18px;
        color: #2D3436;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .stats-mini {
            grid-template-columns: repeat(2, 1fr);
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

        .filters .btn-primary,
        .filters .btn-secondary,
        .filters .btn-export {
            width: 100%;
            justify-content: center;
        }

        .table-custom {
            font-size: 12px;
            min-width: 600px;
        }

        .table-custom th,
        .table-custom td {
            padding: 10px 12px;
        }
    }

    @media (max-width: 480px) {
        .stats-mini {
            grid-template-columns: 1fr 1fr;
        }

        .stat-mini .number {
            font-size: 18px;
        }

        .stat-mini .label {
            font-size: 10px;
        }
    }
</style>

<!-- ===== STATS ===== -->
<div class="stats-mini">
    <div class="stat-mini total">
        <span class="icon"></span>
        <div class="number">{{ $stats['total'] }}</div>
        <div class="label">Total avis</div>
    </div>
    <div class="stat-mini approved">
        <span class="icon"></span>
        <div class="number">{{ $stats['approved'] }}</div>
        <div class="label">Approuves</div>
    </div>
    <div class="stat-mini pending">
        <span class="icon"></span>
        <div class="number">{{ $stats['pending'] }}</div>
        <div class="label">En attente</div>
    </div>
    <div class="stat-mini moyenne">
        <span class="icon"></span>
        <div class="number">{{ $stats['moyenne'] }}</div>
        <div class="label">Note moyenne</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
<div class="filters">
    <form action="{{ route('artisan.avis.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
        <input type="text" name="search" placeholder="Rechercher un avis..." value="{{ request('search') }}"
               style="flex:1;min-width:150px;">

        <select name="approved">
            <option value="">Tous les statuts</option>
            <option value="1" {{ request('approved') == '1' ? 'selected' : '' }}>Approuves</option>
            <option value="0" {{ request('approved') == '0' ? 'selected' : '' }}>En attente</option>
        </select>

        <select name="note">
            <option value="">Toutes les notes</option>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ request('note') == $i ? 'selected' : '' }}>
                    {{ $i }} etoile{{ $i > 1 ? 's' : '' }}
                </option>
            @endfor
        </select>

        <select name="oeuvre_id">
            <option value="">Toutes les oeuvres</option>
            @foreach($oeuvres as $oeuvre)
                <option value="{{ $oeuvre->id }}" {{ request('oeuvre_id') == $oeuvre->id ? 'selected' : '' }}>
                    {{ $oeuvre->titre }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-primary">Filtrer</button>
        <a href="{{ route('artisan.avis.index') }}" class="btn-secondary">Reinitialiser</a>
    </form>

    <a href="{{ route('artisan.avis.export', request()->all()) }}" class="btn-export">Exporter</a>
</div>

<!-- ===== TABLE ===== -->
<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Client</th>
                <th>Oeuvre</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($avis as $item)
                <tr>
                    <td>
                        <div style="font-weight:500;">{{ $item->user->name ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:#95A5A6;">{{ $item->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $item->oeuvre->titre ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $item->note)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                        <div style="font-size:11px;color:#95A5A6;">{{ $item->note }}/5</div>
                    </td>
                    <td style="max-width:200px;">
                        <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $item->commentaire ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <span class="badge-status {{ $item->is_approved ? 'approved' : 'pending' }}">
                            {{ $item->is_approved ? 'Approuve' : 'En attente' }}
                        </span>
                    </td>
                    <td style="font-size:13px;color:#95A5A6;">
                        {{ $item->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}
                        <div style="font-size:11px;">{{ $item->created_at->locale('fr')->isoFormat('HH:mm') }}</div>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap;">
                            <a href="{{ route('artisan.avis.show', $item->id) }}" class="btn-action view" title="Voir">
                                Voir
                            </a>
                            @if(!$item->is_approved)
                                <a href="{{ route('artisan.avis.show', $item->id) }}#repondre" class="btn-action respond" title="Repondre">
                                    Repondre
                                </a>
                            @endif
                            <form action="{{ route('artisan.avis.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action delete" title="Supprimer" onclick="return confirm('Supprimer cet avis ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <span class="icon">A</span>
                            <h3>Aucun avis trouve</h3>
                            <p>Aucun avis ne correspond a vos criteres</p>
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
        Affichage de {{ $avis->firstItem() ?? 0 }} a {{ $avis->lastItem() ?? 0 }} sur {{ $avis->total() }} avis
    </div>
    <div>
        {{ $avis->appends(request()->query())->links() }}
    </div>
</div>

@endsection