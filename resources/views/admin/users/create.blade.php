@extends('layouts.admin')

@section('title', 'Ajouter un utilisateur')

@section('page-title', '➕ Ajouter un utilisateur')

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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .photo-upload {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 2px dashed #E8ECF1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #F8F9FA;
        position: relative;
        overflow: hidden;
    }

    .photo-upload:hover {
        border-color: #6C3CE1;
        background: #F5F0FF;
    }

    .photo-upload img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-upload .placeholder {
        text-align: center;
        color: #95A5A6;
        font-size: 12px;
    }

    .photo-upload .placeholder i {
        font-size: 30px;
        display: block;
        margin-bottom: 6px;
    }

    .photo-upload input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card">
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- ===== PHOTO ===== -->
        <div style="display:flex;justify-content:center;margin-bottom:24px;">
            <div class="photo-upload" id="photoUpload">
                <div class="placeholder" id="photoPlaceholder">
                    <i class="fas fa-user"></i>
                    <span>Photo</span>
                </div>
                <img id="photoPreview" style="display:none;">
                <input type="file" name="photo_profil" id="photoInput" accept="image/*">
            </div>
        </div>

        <!-- ===== INFOS PERSONNELLES ===== -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="name">Nom complet <span style="color:#E74C3C;">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Jean Dupont" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email <span style="color:#E74C3C;">*</span></label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="exemple@email.com" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- ===== PASSWORD ===== -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="password">Mot de passe <span style="color:#E74C3C;">*</span></label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirmer <span style="color:#E74C3C;">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <!-- ===== RÔLE & STATUT ===== -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="role_id">Rôle <span style="color:#E74C3C;">*</span></label>
                <select id="role_id" name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                    <option value="">Sélectionner un rôle</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->nom }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="statut">Statut</label>
                <select id="statut" name="statut" class="form-control">
                    <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                </select>
            </div>
        </div>

        <!-- ===== TÉLÉPHONE & VÉRIFICATION ===== -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" class="form-control"
                       value="{{ old('telephone') }}" placeholder="+33 6 12 34 56 78">
            </div>

            <div class="form-group" style="display:flex;align-items:center;gap:12px;padding-top:28px;">
                <input type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}
                       style="width:18px;height:18px;accent-color:#6C3CE1;">
                <label for="is_verified" style="font-size:13px;color:#2D3436;cursor:pointer;">
                    Utilisateur vérifié
                </label>
            </div>
        </div>

        <!-- ===== INFOS ARTISAN ===== -->
        <div id="artisanFields" style="display:none;border-top:2px solid #F0F0F0;padding-top:20px;margin-top:10px;">
            <h4 style="font-size:15px;font-weight:600;color:#2D3436;margin-bottom:16px;">🛠️ Informations artisan</h4>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="entreprise">Nom de l'entreprise</label>
                    <input type="text" id="entreprise" name="entreprise" class="form-control"
                           value="{{ old('entreprise') }}" placeholder="Atelier Dupont">
                </div>

                <div class="form-group">
                    <label class="form-label" for="siret">Numéro SIRET</label>
                    <input type="text" id="siret" name="siret" class="form-control"
                           value="{{ old('siret') }}" placeholder="12345678901234">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="bio">Biographie</label>
                <textarea id="bio" name="bio" class="form-control" rows="3" placeholder="Présentez-vous...">{{ old('bio') }}</textarea>
            </div>
        </div>

        <!-- ===== ADRESSE ===== -->
        <div style="border-top:2px solid #F0F0F0;padding-top:20px;margin-top:10px;">
            <h4 style="font-size:15px;font-weight:600;color:#2D3436;margin-bottom:16px;">📍 Adresse</h4>

            <div class="form-group">
                <label class="form-label" for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="form-control"
                       value="{{ old('adresse') }}" placeholder="10 Rue des Artisans">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" class="form-control"
                           value="{{ old('ville') }}" placeholder="Paris">
                </div>

                <div class="form-group">
                    <label class="form-label" for="code_postal">Code postal</label>
                    <input type="text" id="code_postal" name="code_postal" class="form-control"
                           value="{{ old('code_postal') }}" placeholder="75001">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="pays">Pays</label>
                <input type="text" id="pays" name="pays" class="form-control"
                       value="{{ old('pays', 'France') }}" placeholder="France">
            </div>
        </div>

        <!-- ===== BOUTONS ===== -->
        <div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:2px solid #F0F0F0;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Créer l'utilisateur
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

    </form>
</div>

<script>
    // ===== PRÉVISUALISATION PHOTO =====
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('photoPreview');
                const placeholder = document.getElementById('photoPlaceholder');
                preview.src = event.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    // ===== AFFICHAGE CHAMPS ARTISAN =====
    document.getElementById('role_id').addEventListener('change', function() {
        const roleId = this.value;
        const artisanFields = document.getElementById('artisanFields');

        // Récupérer le rôle sélectionné
        const roleText = this.options[this.selectedIndex].text;

        if (roleText === 'Artisan') {
            artisanFields.style.display = 'block';
        } else {
            artisanFields.style.display = 'none';
        }
    });

    // Vérifier si le rôle sélectionné par défaut est Artisan
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const selectedText = roleSelect.options[roleSelect.selectedIndex]?.text;
        if (selectedText === 'Artisan') {
            document.getElementById('artisanFields').style.display = 'block';
        }
    });
</script>

@endsection