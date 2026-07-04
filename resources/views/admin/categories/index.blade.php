@extends('layouts.admin')

@section('title', 'Gestion des catégories')

@section('page-title', 'Catégories')

@section('content')

<style>
    /* ===== STATS ===== */
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

    .stat-mini .icon { font-size: 28px; display:block; margin-bottom:4px; }

    .stat-mini.total { border-left-color: #6C3CE1; }
    .stat-mini.parents { border-left-color: #8B6CE6; }
    .stat-mini.actif { border-left-color: #2ECC71; }
    .stat-mini.inactif { border-left-color: #E74C3C; }

    /* ===== FILTRES ===== */
    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        align-items: center;
        background: white;
        padding: 16px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .filters select, .filters input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
        color: #2D3436;
    }

    .filters select:focus, .filters input:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.1);
    }

    .filters .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        flex: 1;
    }

    /* ===== BOUTONS ===== */
    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(108,60,225,0.3);
        color: white;
    }

    .btn-secondary {
        background: #F0F2F5;
        color: #2D3436;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
    }

    .btn-secondary:hover {
        background: #E8ECF1;
        transform: translateY(-2px);
    }

    /* ===== TABLE ===== */
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
    }

    .table-custom th {
        background: #F8F9FA;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #2D3436;
        border-bottom: 2px solid #E8ECF1;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-custom td {
        padding: 12px 16px;
        border-bottom: 1px solid #F0F0F0;
        vertical-align: middle;
    }

    .table-custom tr:hover {
        background: #F8F9FA;
    }

    .categorie-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: #F8F9FA;
        border: 2px solid #E8ECF1;
        transition: all 0.3s ease;
    }

    .categorie-icon:hover {
        border-color: #6C3CE1;
        transform: scale(1.05);
    }

    .categorie-nom {
        font-weight: 600;
        color: #2D3436;
    }

    .parent-info {
        font-size: 11px;
        color: #95A5A6;
        margin-top: 2px;
    }

    .parent-info i {
        margin-right: 4px;
    }

    .badge-type {
        font-size: 11px;
        padding: 3px 12px;
        border-radius: 50px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-type.parent { background: #E8E0F8; color: #6C3CE1; }
    .badge-type.child { background: #D4E8FC; color: #3498DB; }

    .badge-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.active { background: #D4F4E6; color: #2ECC71; }
    .badge-status.inactive { background: #FCD4D4; color: #E74C3C; }

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
        background: transparent;
    }

    .btn-action.view { background: #E8E0F8; color: #6C3CE1; }
    .btn-action.edit { background: #D4E8FC; color: #3498DB; }
    .btn-action.delete { background: #FCD4D4; color: #E74C3C; }
    .btn-action.toggle { background: #FDE8D0; color: #F39C12; }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.view:hover { background: #6C3CE1; color: white; }
    .btn-action.edit:hover { background: #3498DB; color: white; }
    .btn-action.delete:hover { background: #E74C3C; color: white; }
    .btn-action.toggle:hover { background: #F39C12; color: white; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95A5A6;
    }

    .empty-state i {
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

    /* ===== PAGINATION ===== */
    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }

    .pagination-info .info {
        color: #95A5A6;
        font-size: 13px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .stats-mini {
            grid-template-columns: repeat(2, 1fr);
        }
        .filters .filter-group {
            flex-direction: column;
            width: 100%;
        }
        .filters .filter-group input,
        .filters .filter-group select {
            width: 100%;
        }
        .table-custom {
            font-size: 12px;
        }
        .table-custom th,
        .table-custom td {
            padding: 8px 10px;
        }
        .categorie-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }
        .pagination-info {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .stats-mini {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .stat-mini {
            padding: 12px;
        }
        .stat-mini .number {
            font-size: 18px;
        }
        .btn-action {
            padding: 4px 8px;
            font-size: 11px;
        }
    }
</style>

<!-- ===== STATS ===== -->
<div class="stats-mini">
    <div class="stat-mini total">
        <span class="icon"></span>
        <div class="number">{{ $stats['total'] }}</div>
        <div class="label">Total catégories</div>
    </div>
    <div class="stat-mini parents">
        <span class="icon"></span>
        <div class="number">{{ $stats['parents'] }}</div>
        <div class="label">Principales</div>
    </div>
    <div class="stat-mini actif">
        <span class="icon"></span>
        <div class="number">{{ $stats['actives'] }}</div>
        <div class="label">Actives</div>
    </div>
    <div class="stat-mini inactif">
        <span class="icon"></span>
        <div class="number">{{ $stats['inactives'] }}</div>
        <div class="label">Inactives</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
<div class="filters">
    <form action="{{ route('admin.categories.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
        <div class="filter-group">
            <input type="text" name="search" placeholder="🔍 Rechercher..." value="{{ request('search') }}">

            <select name="type">
                <option value="">Tous les types</option>
                <option value="parent" {{ request('type') == 'parent' ? 'selected' : '' }}>Principales</option>
                <option value="child" {{ request('type') == 'child' ? 'selected' : '' }}>Sous-catégories</option>
            </select>

            <select name="active">
                <option value="">Tous les statuts</option>
                <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Actif</option>
                <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactif</option>
            </select>

            <button type="submit" class="btn-primary">
                <i class="fas fa-filter"></i> Filtrer
            </button>

            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                <i class="fas fa-undo"></i> Réinitialiser
            </a>
        </div>
    </form>

    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Nouvelle catégorie
        </a>
      
    </div>
</div>

<!-- ===== TABLE ===== -->
<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width:60px;">Icône</th>
                <th>Nom</th>
                <th>Type</th>
                <th>Description</th>
                <th style="text-align:center;">Œuvres</th>
                <th>Statut</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $categorie)
                <tr>
                    <td>
                        <div class="categorie-icon">
                            {{ $categorie->icone ?? '' }}
                        </div>
                    </td>
                    <td>
                        <div class="categorie-nom">{{ $categorie->nom }}</div>
                        @if($categorie->parent)
                            <div class="parent-info">
                                <i class="fas fa-arrow-up"></i> {{ $categorie->parent->nom }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($categorie->isParent())
                            <span class="badge-type parent">
                                <i class="fas fa-folder"></i> Principale
                            </span>
                        @else
                            <span class="badge-type child">
                                <i class="fas fa-file"></i> Sous-catégorie
                            </span>
                        @endif
                    </td>
                    <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#95A5A6;font-size:13px;">
                        {{ $categorie->description ?? '-' }}
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:600;color:#6C3CE1;font-size:16px;">{{ $categorie->oeuvres_count ?? 0 }}</span>
                    </td>
                    <td>
                        <span class="badge-status {{ $categorie->is_active ? 'active' : 'inactive' }}">
                            {{ $categorie->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap;">
                            <a href="{{ route('admin.categories.show', $categorie->id) }}" class="btn-action view" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $categorie->id) }}" class="btn-action edit" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.categories.toggle-active', $categorie->id) }}" class="btn-action toggle"
                               title="{{ $categorie->is_active ? 'Désactiver' : 'Activer' }}"
                               onclick="return confirm('{{ $categorie->is_active ? 'Désactiver' : 'Activer' }} cette catégorie ?')">
                                <i class="fas {{ $categorie->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $categorie->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action delete" title="Supprimer" onclick="return confirm('Supprimer cette catégorie ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-tags"></i>
                            <h3>Aucune catégorie trouvée</h3>
                            <p>Commencez par créer votre première catégorie</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn-primary" style="margin-top:16px;">
                                <i class="fas fa-plus"></i> Créer une catégorie
                            </a>
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
        Affichage de {{ $categories->firstItem() ?? 0 }} à {{ $categories->lastItem() ?? 0 }} sur {{ $categories->total() }} catégories
    </div>
    <div>
        {{ $categories->appends(request()->query())->links() }}
    </div>
</div>

@endsection