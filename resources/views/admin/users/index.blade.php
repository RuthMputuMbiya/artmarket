@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('page-title', 'Utilisateurs')

@section('content')

<style>
    /* ===== STATS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #eef0f2;
    }

    .stat-card .number {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1.2;
    }

    .stat-card .label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 2px;
    }

    .stat-card .icon {
        font-size: 20px;
        display: block;
        margin-bottom: 4px;
    }

    /* ===== FILTRES ===== */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        align-items: center;
        background: white;
        padding: 14px 18px;
        border-radius: 10px;
        border: 1px solid #eef0f2;
    }

    .filter-bar input,
    .filter-bar select {
        padding: 8px 14px;
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        font-size: 13px;
        font-family: inherit;
        outline: none;
        transition: all 0.2s;
        background: white;
        min-height: 40px;
        color: #1f2937;
    }

    .filter-bar input:focus,
    .filter-bar select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
    }

    .filter-bar select {
        min-width: 130px;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 32px;
    }

    .filter-bar .search-input {
        flex: 1;
        min-width: 200px;
    }

    /* ===== BOUTONS ===== */
    .btn {
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
        font-family: inherit;
        min-height: 40px;
        text-decoration: none;
    }

    .btn-primary {
        background: #4f46e5;
        color: white;
    }

    .btn-primary:hover {
        background: #4338ca;
        color: white;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 1.5px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        color: #1f2937;
    }

    .btn-export {
        background: #1f2937;
        color: white;
    }

    .btn-export:hover {
        background: #111827;
        color: white;
    }

    /* ===== TABLE ===== */
    .table-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #eef0f2;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .table-scroll {
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 950px;
    }

    .table-custom thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-custom thead th {
        padding: 12px 14px;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        white-space: nowrap;
    }

    .table-custom tbody td {
        padding: 10px 14px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        color: #1f2937;
    }

    .table-custom tbody tr:hover {
        background: #f9fafb;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    /* ===== AVATAR ===== */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #e5e7eb;
        flex-shrink: 0;
        background: #f3f4f6;
    }

    .user-name {
        font-weight: 600;
        color: #1f2937;
    }

    .user-company {
        font-size: 11px;
        color: #6b7280;
    }

    /* ===== BADGES ===== */
    .badge {
        padding: 3px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        letter-spacing: 0.2px;
    }

    .badge-admin { background: #eef2ff; color: #4f46e5; }
    .badge-artisan { background: #ecfdf5; color: #059669; }
    .badge-client { background: #eff6ff; color: #2563eb; }

    .badge-actif { background: #ecfdf5; color: #059669; }
    .badge-inactif { background: #f3f4f6; color: #6b7280; }
    .badge-suspendu { background: #fef2f2; color: #dc2626; }

    .badge-verified {
        color: #059669;
        font-weight: 600;
        font-size: 12px;
    }

    .badge-unverified {
        color: #6b7280;
        font-weight: 600;
        font-size: 12px;
    }

    /* ===== ACTIONS (TOUT SUR UNE LIGNE) ===== */
    .actions {
        display: flex;
        gap: 4px;
        align-items: center;
        justify-content: flex-start;
        flex-wrap: nowrap;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: inherit;
        font-weight: 500;
        flex-shrink: 0;
    }

    .action-btn i {
        font-size: 13px;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .action-view { background: #eef2ff; color: #4f46e5; }
    .action-view:hover { background: #4f46e5; color: white; }

    .action-edit { background: #eff6ff; color: #2563eb; }
    .action-edit:hover { background: #2563eb; color: white; }

    .action-delete { background: #fef2f2; color: #dc2626; }
    .action-delete:hover { background: #dc2626; color: white; }

    .action-verify { background: #ecfdf5; color: #059669; }
    .action-verify:hover { background: #059669; color: white; }

    .action-toggle { background: #fef3c7; color: #d97706; }
    .action-toggle:hover { background: #d97706; color: white; }

    .action-dashboard { background: #e0e7ff; color: #4f46e5; }
    .action-dashboard:hover { background: #4f46e5; color: white; }

    /* ===== PAGINATION ===== */
    .pagination-wrap {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrap .pagination {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }

    .pagination-wrap .pagination .page-item .page-link {
        padding: 6px 13px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        color: #374151;
        transition: all 0.2s;
        font-size: 13px;
        text-decoration: none;
        display: inline-block;
    }

    .pagination-wrap .pagination .page-item.active .page-link {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }

    .pagination-wrap .pagination .page-item .page-link:hover {
        background: #f3f4f6;
        border-color: #4f46e5;
        color: #4f46e5;
    }

    /* ===== TOOLTIP ===== */
    .action-btn {
        position: relative;
    }

    .action-btn:hover::after {
        content: attr(title);
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%);
        background: #1f2937;
        color: white;
        font-size: 10px;
        padding: 3px 10px;
        border-radius: 4px;
        white-space: nowrap;
        font-weight: 500;
        z-index: 10;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #6b7280;
    }

    .empty-state .icon {
        font-size: 40px;
        display: block;
        margin-bottom: 12px;
        color: #d1d5db;
    }

    .empty-state .title {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .empty-state .sub {
        font-size: 13px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-bar input,
        .filter-bar select {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-custom {
            min-width: 700px;
        }

        .actions {
            gap: 3px;
        }

        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 11px;
        }

        .action-btn i {
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- ===== STATISTIQUES ===== -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="icon"></span>
        <div class="number">{{ $stats['total'] ?? $users->total() }}</div>
        <div class="label">Total utilisateurs</div>
    </div>
    <div class="stat-card" style="border-left: 3px solid #4f46e5;">
        <span class="icon"></span>
        <div class="number">{{ $stats['admins'] ?? 0 }}</div>
        <div class="label">Administrateurs</div>
    </div>
    <div class="stat-card" style="border-left: 3px solid #059669;">
        <span class="icon"></span>
        <div class="number">{{ $stats['artisans'] ?? 0 }}</div>
        <div class="label">Artisans</div>
    </div>
    <div class="stat-card" style="border-left: 3px solid #2563eb;">
        <span class="icon"></span>
        <div class="number">{{ $stats['clients'] ?? 0 }}</div>
        <div class="label">Clients</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
<div class="filter-bar">
    <form action="{{ route('admin.users.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;flex:1;width:100%;">
        <input type="text" name="search" placeholder="Rechercher par nom, email..." value="{{ request('search') }}" class="search-input">

        <select name="role">
            <option value="">Tous les rôles</option>
            @foreach($roles as $role)
                <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                    {{ $role->nom }}
                </option>
            @endforeach
        </select>

        <select name="statut">
            <option value="">Tous les statuts</option>
            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
            <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
            <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
        </select>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filtrer
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Réinitialiser
        </a>
    </form>

    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter
        </a>
       
    </div>
</div>

<!-- ===== TABLEAU ===== -->
<div class="table-wrapper">
    <div class="table-scroll">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th style="width:100px;">Téléphone</th>
                    <th style="width:110px;">Rôle</th>
                    <th style="width:90px;">Statut</th>
                    <th style="width:80px;">Vérifié</th>
                    <th style="width:100px;">Date</th>
                    <th style="text-align:center;width:200px;min-width:200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="user-cell">
                                <img src="{{ $user->photo_profil ? asset('storage/' . $user->photo_profil) : asset('images/default-avatar.png') }}"
                                     alt="{{ $user->name }}" class="user-avatar"
                                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                    @if($user->entreprise)
                                        <div class="user-company">{{ $user->entreprise }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->telephone ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $user->role->slug ?? 'client' }}">
                                {{ $user->role->nom ?? 'Client' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $user->statut ?? 'actif' }}">
                                {{ ucfirst($user->statut ?? 'Actif') }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_verified)
                                <span class="badge-verified"><i class="fas fa-check-circle"></i> Oui</span>
                            @else
                                <span class="badge-unverified"><i class="fas fa-times-circle"></i> Non</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:#6b7280;">
                            {{ \Carbon\Carbon::parse($user->created_at)->locale('fr')->isoFormat('DD MMM YYYY') }}
                        </td>
                        <td>
                            <div class="actions">
                                <!-- Voir -->
                                <a href="{{ route('admin.users.show', $user->id) }}" class="action-btn action-view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Modifier -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn action-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Vérifier (uniquement pour les artisans non vérifiés) -->
                                @if(isset($user->role) && $user->role->slug == 'artisan' && !$user->is_verified)
                                    <a href="{{ route('admin.users.verify', $user->id) }}" class="action-btn action-verify" title="Vérifier" onclick="return confirm('Vérifier cet artisan ?')">
                                        <i class="fas fa-check-double"></i>
                                    </a>
                                @endif

                                <!-- Suspendre/Activer (uniquement pour les non-admins) -->
                                @if(!isset($user->role) || $user->role->slug != 'admin')
                                    <a href="{{ route('admin.users.toggle-status', $user->id) }}" class="action-btn action-toggle" title="{{ $user->statut === 'actif' ? 'Suspendre' : 'Activer' }}"
                                       onclick="return confirm('Changer le statut de cet utilisateur ?')">
                                        <i class="fas {{ $user->statut === 'actif' ? 'fa-pause' : 'fa-play' }}"></i>
                                    </a>

                                    <!-- Supprimer -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-delete" title="Supprimer" onclick="return confirm('Supprimer cet utilisateur ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Dashboard Artisan (uniquement pour les artisans) -->
                                @if(isset($user->role) && $user->role->slug == 'artisan')
                                    <a href="{{ route('artisan.dashboard') }}" class="action-btn action-dashboard" title="Tableau de bord">
                                        <i class="fas fa-chart-simple"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <span class="icon"></span>
                                <div class="title">Aucun utilisateur trouvé</div>
                                <div class="sub">Essayez de modifier vos filtres ou d'ajouter un utilisateur</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ===== PAGINATION ===== -->
<div class="pagination-wrap">
    {{ $users->appends(request()->query())->links() }}
</div>

@endsection