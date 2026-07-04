@extends('layouts.admin')

@section('title', 'Modifier l\'oeuvre - ' . $oeuvre->titre)

@section('page-title', 'Modifier l\'oeuvre')

@section('content')

<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .form-group label .required {
        color: #E74C3C;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #E8ECF1;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 4px rgba(108,60,225,0.10);
    }

    .form-group .help-text {
        font-size: 12px;
        color: #95A5A6;
        margin-top: 4px;
        display: block;
    }

    .form-group .error {
        color: #E74C3C;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 8px;
    }

    .form-check input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #6C3CE1;
        cursor: pointer;
    }

    .form-check label {
        font-weight: 500;
        color: #2D3436;
        font-size: 14px;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #E8ECF1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
    }

    .btn-secondary {
        background: #F0F2F5;
        color: #2D3436;
        border: 2px solid #E8ECF1;
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
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

    .image-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
        margin-top: 10px;
    }

    .image-preview .img-item {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #E8ECF1;
        height: 100px;
    }

    .image-preview .img-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview .img-item .principal-badge {
        position: absolute;
        bottom: 4px;
        right: 4px;
        background: #6C3CE1;
        color: white;
        padding: 2px 8px;
        border-radius: 50px;
        font-size: 9px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .form-row-3 {
            grid-template-columns: 1fr 1fr;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions .btn-primary,
        .form-actions .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .form-row-3 {
            grid-template-columns: 1fr;
        }
        .form-container {
            padding: 16px;
        }
    }
</style>

<div class="form-container">
    <form action="{{ route('admin.oeuvres.update', $oeuvre->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- ===== TITRE ET SLUG ===== -->
        <div class="form-row">
            <div class="form-group">
                <label>Titre <span class="required">*</span></label>
                <input type="text" name="titre" value="{{ old('titre', $oeuvre->titre) }}" required>
                @error('titre')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Slug</label>
                <input type="text" value="{{ $oeuvre->slug }}" disabled style="background:#F8F9FA;color:#95A5A6;">
                <span class="help-text">Le slug est genere automatiquement</span>
            </div>
        </div>

        <!-- ===== DESCRIPTION ===== -->
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4">{{ old('description', $oeuvre->description) }}</textarea>
            @error('description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <!-- ===== PRIX ET DEVISES ===== -->
        <div class="form-row-3">
            <div class="form-group">
                <label>Prix <span class="required">*</span></label>
                <input type="number" name="prix" value="{{ old('prix', $oeuvre->prix) }}" step="0.01" min="0" required>
                @error('prix')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Prix promo</label>
                <input type="number" name="prix_promo" value="{{ old('prix_promo', $oeuvre->prix_promo) }}" step="0.01" min="0">
                <span class="help-text">Laisser vide si pas de promotion</span>
                @error('prix_promo')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Devise <span class="required">*</span></label>
                <select name="devise_id" required>
                    @foreach($devises as $devise)
                        <option value="{{ $devise->id }}" {{ old('devise_id', $oeuvre->devise_id) == $devise->id ? 'selected' : '' }}>
                            {{ $devise->code }} - {{ $devise->symbole }}
                        </option>
                    @endforeach
                </select>
                @error('devise_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- ===== CATEGORIE ET ARTISAN ===== -->
        <div class="form-row">
            <div class="form-group">
                <label>Categorie</label>
                <select name="categorie_id">
                    <option value="">Non classe</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id', $oeuvre->categorie_id) == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
                @error('categorie_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Artisan</label>
                <select name="user_id">
                    <option value="">Selectionner un artisan</option>
                    @foreach($artisans as $artisan)
                        <option value="{{ $artisan->id }}" {{ old('user_id', $oeuvre->user_id) == $artisan->id ? 'selected' : '' }}>
                            {{ $artisan->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- ===== STOCK ET STATUT ===== -->
        <div class="form-row-3">
            <div class="form-group">
                <label>Stock <span class="required">*</span></label>
                <input type="number" name="stock" value="{{ old('stock', $oeuvre->stock) }}" min="0" required>
                @error('stock')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Statut <span class="required">*</span></label>
                <select name="statut" required>
                    @foreach($statuts as $statut)
                        <option value="{{ $statut }}" {{ old('statut', $oeuvre->statut) == $statut ? 'selected' : '' }}>
                            {{ ucfirst($statut) }}
                        </option>
                    @endforeach
                </select>
                @error('statut')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Materiaux</label>
                <input type="text" name="materiaux" value="{{ old('materiaux', $oeuvre->materiaux) }}">
                @error('materiaux')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- ===== DIMENSIONS ===== -->
        <div class="form-group">
            <label>Dimensions</label>
            <input type="text" name="dimensions" value="{{ old('dimensions', $oeuvre->dimensions) }}" placeholder="Ex: 50 x 70 cm">
            @error('dimensions')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <!-- ===== CHECKBOXES ===== -->
        <div class="form-row">
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $oeuvre->is_featured) ? 'checked' : '' }}>
                    <label>Mettre en avant (A la une)</label>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $oeuvre->is_available) ? 'checked' : '' }}>
                    <label>Disponible a la vente</label>
                </div>
            </div>
        </div>

        <!-- ===== IMAGES EXISTANTES ===== -->
        @if($oeuvre->images->count() > 0)
            <div class="form-group">
                <label>Images actuelles</label>
                <div class="image-preview">
                    @foreach($oeuvre->images as $image)
                        <div class="img-item">
                            <img src="{{ $image->url }}" alt="Image">
                            @if($image->is_principal)
                                <span class="principal-badge">Principal</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <span class="help-text">L'image principale est marquee. Pour changer, modifiez dans l'interface des images.</span>
            </div>
        @endif

        <!-- ===== BOUTONS ===== -->
        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Mettre a jour
            </button>
            <a href="{{ route('admin.oeuvres.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Annuler
            </a>
            <a href="{{ route('admin.oeuvres.show', $oeuvre->id) }}" class="btn-secondary">
                <i class="fas fa-eye"></i> Voir l'oeuvre
            </a>
        </div>

    </form>
</div>

@endsection