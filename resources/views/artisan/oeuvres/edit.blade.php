@extends('layouts.artisan')

@section('title', 'Modifier une oeuvre')

@section('page-title', 'Modifier l\'oeuvre')

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

    .image-upload-area {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
        padding: 16px;
        border: 2px dashed #E8ECF1;
        border-radius: 12px;
        min-height: 150px;
        transition: all 0.3s ease;
        background: #F8F9FA;
    }

    .image-upload-area:hover {
        border-color: #6C3CE1;
        background: #F5F0FF;
    }

    .image-upload-area .upload-box {
        border: 2px dashed #D4D4D4;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100px;
    }

    .image-upload-area .upload-box:hover {
        border-color: #6C3CE1;
        background: #F5F0FF;
    }

    .image-upload-area .upload-box i {
        font-size: 30px;
        color: #95A5A6;
        margin-bottom: 4px;
    }

    .image-upload-area .upload-box span {
        font-size: 12px;
        color: #95A5A6;
    }

    .image-upload-area .preview-box {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #E8ECF1;
        aspect-ratio: 1;
        background: white;
    }

    .image-upload-area .preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-upload-area .preview-box .remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: none;
        background: rgba(231,76,60,0.9);
        color: white;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .image-upload-area .preview-box .remove-btn:hover {
        transform: scale(1.1);
    }

    .image-upload-area .preview-box .main-badge {
        position: absolute;
        bottom: 4px;
        left: 4px;
        background: rgba(108,60,225,0.9);
        color: white;
        font-size: 9px;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .image-upload-area .preview-box .set-main-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        background: rgba(46,204,113,0.9);
        color: white;
        border: none;
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 9px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-upload-area .preview-box .set-main-btn:hover {
        transform: scale(1.05);
    }

    #fileInput {
        display: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 12px 28px;
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

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
        color: white;
    }

    .btn-secondary {
        background: #F0F2F5;
        color: #2D3436;
        border: 2px solid #E8ECF1;
        padding: 12px 28px;
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

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card">
    <form action="{{ route('artisan.oeuvres.update', $oeuvre->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="titre">Titre <span style="color:#E74C3C;">*</span></label>
                <input type="text" id="titre" name="titre" class="form-control @error('titre') is-invalid @enderror"
                       value="{{ old('titre', $oeuvre->titre) }}" required>
                @error('titre')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="categorie_id">Categorie</label>
                <select id="categorie_id" name="categorie_id" class="form-control">
                    <option value="">Selectionner une categorie</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id', $oeuvre->categorie_id) == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description <span style="color:#E74C3C;">*</span></label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                      rows="4">{{ old('description', $oeuvre->description) }}</textarea>
            @error('description')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="prix">Prix <span style="color:#E74C3C;">*</span></label>
                <div style="display:flex;gap:8px;">
                    <input type="number" id="prix" name="prix" class="form-control @error('prix') is-invalid @enderror"
                           value="{{ old('prix', $oeuvre->prix) }}" step="0.01" min="0" required style="flex:1;">
                    <select id="devise_id" name="devise_id" class="form-control" style="width:auto;min-width:80px;">
                        @foreach($devises as $devise)
                            <option value="{{ $devise->id }}" {{ old('devise_id', $oeuvre->devise_id) == $devise->id ? 'selected' : '' }}>
                                {{ $devise->symbole }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('prix')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="prix_promo">Prix promo</label>
                <input type="number" id="prix_promo" name="prix_promo" class="form-control @error('prix_promo') is-invalid @enderror"
                       value="{{ old('prix_promo', $oeuvre->prix_promo) }}" step="0.01" min="0">
                @error('prix_promo')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="stock">Stock <span style="color:#E74C3C;">*</span></label>
                <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror"
                       value="{{ old('stock', $oeuvre->stock) }}" min="0" required>
                @error('stock')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="stock_min">Seuil d'alerte</label>
                <input type="number" id="stock_min" name="stock_min" class="form-control"
                       value="{{ old('stock_min', $oeuvre->stock_min ?? 1) }}" min="0">
                <small style="color:#95A5A6;font-size:11px;">Vous serez alerte en dessous de ce seuil</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="materiaux">Materiaux</label>
                <input type="text" id="materiaux" name="materiaux" class="form-control"
                       value="{{ old('materiaux', $oeuvre->materiaux) }}" placeholder="Bois, bronze, huile...">
            </div>

            <div class="form-group">
                <label class="form-label" for="dimensions">Dimensions</label>
                <input type="text" id="dimensions" name="dimensions" class="form-control"
                       value="{{ old('dimensions', $oeuvre->dimensions) }}" placeholder="80x60 cm">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="statut">Statut</label>
                <select id="statut" name="statut" class="form-control">
                    @foreach($statuts as $statut)
                        <option value="{{ $statut }}" {{ old('statut', $oeuvre->statut) == $statut ? 'selected' : '' }}>
                            {{ ucfirst($statut) }}
                        </option>
                    @endforeach
                </select>
                <small style="color:#95A5A6;font-size:11px;">"En attente" necessite validation admin</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="quantite_par_commande">Quantite max par commande</label>
                <input type="number" id="quantite_par_commande" name="quantite_par_commande" class="form-control"
                       value="{{ old('quantite_par_commande', $oeuvre->quantite_par_commande ?? 5) }}" min="1">
            </div>
        </div>

        <!-- Images existantes -->
        @if($oeuvre->images->count() > 0)
            <div class="form-group">
                <label class="form-label">Images existantes</label>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px;">
                    @foreach($oeuvre->images as $image)
                        <div style="position:relative;border-radius:10px;overflow:hidden;border:2px solid {{ $image->is_principal ? '#6C3CE1' : '#E8ECF1' }};aspect-ratio:1;">
                            <img src="{{ asset('storage/' . $image->url) }}" alt="Image" style="width:100%;height:100%;object-fit:cover;">
                            <div style="position:absolute;bottom:4px;left:4px;background:{{ $image->is_principal ? 'rgba(108,60,225,0.9)' : 'rgba(0,0,0,0.6)' }};color:white;font-size:9px;padding:2px 8px;border-radius:4px;font-weight:600;">
                                {{ $image->is_principal ? 'Principale' : 'Image' }}
                            </div>
                            <div style="position:absolute;top:4px;right:4px;display:flex;gap:4px;">
                                @if(!$image->is_principal)
                                    <a href="{{ route('artisan.oeuvres.set-principal', [$oeuvre->id, $image->id]) }}"
                                       style="width:24px;height:24px;border-radius:50%;background:rgba(46,204,113,0.9);color:white;border:none;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                        <i class="fas fa-star"></i>
                                    </a>
                                @endif
                                <a href="{{ route('artisan.oeuvres.delete-image', [$oeuvre->id, $image->id]) }}"
                                   style="width:24px;height:24px;border-radius:50%;background:rgba(231,76,60,0.9);color:white;border:none;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center;text-decoration:none;"
                                   onclick="return confirm('Supprimer cette image ?')">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Ajouter des images -->
        <div class="form-group">
            <label class="form-label">Ajouter des images</label>
            <div class="image-upload-area" id="imageUploadArea">
                <div class="upload-box" id="uploadBox">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Cliquez pour ajouter<br><small style="color:#95A5A6;">JPG, PNG, GIF, WEBP (max 5Mo)</small></span>
                </div>
            </div>
            <input type="file" id="fileInput" name="images[]" accept="image/*" multiple>
            @error('images.*')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div style="display:flex;gap:12px;padding-top:20px;border-top:2px solid #F0F0F0;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Mettre a jour
            </button>
            <a href="{{ route('artisan.oeuvres.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

    </form>
</div>

<script>
    const fileInput = document.getElementById('fileInput');
    const uploadArea = document.getElementById('imageUploadArea');
    const uploadBox = document.getElementById('uploadBox');

    uploadBox.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);

        files.forEach((file) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'preview-box';

                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.alt = 'Apercu';

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-btn';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.type = 'button';
                    removeBtn.onclick = function(e) {
                        e.stopPropagation();
                        previewDiv.remove();
                        updateFileInput();
                    };

                    previewDiv.appendChild(img);
                    previewDiv.appendChild(removeBtn);

                    uploadArea.insertBefore(previewDiv, uploadBox);
                    updateFileInput();
                };
                reader.readAsDataURL(file);
            }
        });
    });

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        const files = fileInput.files;
        const previewCount = document.querySelectorAll('.preview-box').length;

        if (previewCount === 0) {
            fileInput.value = '';
            return;
        }

        for (let i = 0; i < files.length; i++) {
            dataTransfer.items.add(files[i]);
        }
        fileInput.files = dataTransfer.files;
    }

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#6C3CE1';
        this.style.background = '#F5F0FF';
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#E8ECF1';
        this.style.background = '#F8F9FA';
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#E8ECF1';
        this.style.background = '#F8F9FA';

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });
</script>

@endsection