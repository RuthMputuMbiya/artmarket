@extends('layouts.admin')

@section('title', 'Ajouter une catégorie')

@section('page-title', '➕ Ajouter une catégorie')

@section('content')

<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        outline: none;
        background: white;
        color: #2D3436;
    }

    .form-control:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.1);
    }

    .form-control.is-invalid {
        border-color: #E74C3C;
    }

    .form-error {
        color: #E74C3C;
        font-size: 12px;
        margin-top: 4px;
    }

    .icon-picker {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
        gap: 8px;
        padding: 12px;
        background: #F8F9FA;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        max-height: 200px;
        overflow-y: auto;
    }

    .icon-picker .icon-option {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: white;
    }

    .icon-picker .icon-option:hover {
        border-color: #6C3CE1;
        background: #F5F0FF;
        transform: scale(1.1);
    }

    .icon-picker .icon-option.selected {
        border-color: #6C3CE1;
        background: #E8E0F8;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.2);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <!-- ===== NOM ===== -->
        <div class="form-group">
            <label class="form-label" for="nom">Nom de la catégorie <span style="color:#E74C3C;">*</span></label>
            <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                   value="{{ old('nom') }}" placeholder="Ex: Peinture" required>
            @error('nom')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- ===== DESCRIPTION ===== -->
        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3"
                      placeholder="Description de la catégorie...">{{ old('description') }}</textarea>
        </div>

        <!-- ===== ICÔNE ===== -->
        <div class="form-group">
            <label class="form-label">Icône <span style="color:#95A5A6;font-weight:400;">(cliquez pour sélectionner)</span></label>
            <input type="hidden" id="icone" name="icone" value="{{ old('icone') }}">

            <div class="icon-picker">
                <div class="icon-option" data-icon="🎨">🎨</div>
                <div class="icon-option" data-icon="🖼️">🖼️</div>
                <div class="icon-option" data-icon="🗿">🗿</div>
                <div class="icon-option" data-icon="🏺">🏺</div>
                <div class="icon-option" data-icon="💎">💎</div>
                <div class="icon-option" data-icon="📸">📸</div>
                <div class="icon-option" data-icon="✏️">✏️</div>
                <div class="icon-option" data-icon="🧵">🧵</div>
                <div class="icon-option" data-icon="🔮">🔮</div>
                <div class="icon-option" data-icon="🪑">🪑</div>
                <div class="icon-option" data-icon="💻">💻</div>
                <div class="icon-option" data-icon="🎭">🎭</div>
                <div class="icon-option" data-icon="🎪">🎪</div>
                <div class="icon-option" data-icon="🎵">🎵</div>
                <div class="icon-option" data-icon="📚">📚</div>
                <div class="icon-option" data-icon="🧶">🧶</div>
                <div class="icon-option" data-icon="🪚">🪚</div>
                <div class="icon-option" data-icon="🔨">🔨</div>
                <div class="icon-option" data-icon="🖌️">🖌️</div>
                <div class="icon-option" data-icon="📐">📐</div>
                <div class="icon-option" data-icon="🧿">🧿</div>
                <div class="icon-option" data-icon="🎯">🎯</div>
                <div class="icon-option" data-icon="🌟">🌟</div>
                <div class="icon-option" data-icon="✨">✨</div>
                <div class="icon-option" data-icon="🎈">🎈</div>
                <div class="icon-option" data-icon="🎀">🎀</div>
                <div class="icon-option" data-icon="🎁">🎁</div>
                <div class="icon-option" data-icon="🎊">🎊</div>
            </div>
            <p style="font-size:12px;color:#95A5A6;margin-top:4px;">
                Icône sélectionnée: <span id="selectedIconDisplay" style="font-size:20px;">{{ old('icone') ?? 'Aucune' }}</span>
            </p>
        </div>

        <!-- ===== STATUT ===== -->
        <div class="form-group" style="display:flex;align-items:center;gap:12px;padding:12px 0;">
            <input type="checkbox" id="is_active" name="is_active" value="1" checked
                   style="width:18px;height:18px;accent-color:#6C3CE1;">
            <label for="is_active" style="font-size:14px;color:#2D3436;cursor:pointer;">
                Catégorie active
            </label>
        </div>

        <!-- ===== BOUTONS ===== -->
        <div style="display:flex;gap:12px;padding-top:20px;border-top:2px solid #F0F0F0;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Créer la catégorie
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

    </form>
</div>

<script>
    // ===== SÉLECTION D'ICÔNE =====
    document.querySelectorAll('.icon-option').forEach(option => {
        option.addEventListener('click', function() {
            // Retirer la sélection précédente
            document.querySelectorAll('.icon-option').forEach(opt => opt.classList.remove('selected'));

            // Sélectionner
            this.classList.add('selected');
            const icon = this.dataset.icon;
            document.getElementById('icone').value = icon;
            document.getElementById('selectedIconDisplay').textContent = icon;
        });
    });

    // Si une icône est déjà sélectionnée (erreur de validation)
    document.addEventListener('DOMContentLoaded', function() {
        const currentIcon = document.getElementById('icone').value;
        if (currentIcon) {
            document.querySelectorAll('.icon-option').forEach(opt => {
                if (opt.dataset.icon === currentIcon) {
                    opt.classList.add('selected');
                }
            });
            document.getElementById('selectedIconDisplay').textContent = currentIcon;
        }
    });
</script>

@endsection