@extends('layouts.client')

@section('title', 'Donner un avis')

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

    .stars-rating {
        display: flex;
        gap: 8px;
        font-size: 32px;
        cursor: pointer;
    }

    .stars-rating .star {
        color: #D4D4D4;
        transition: all 0.3s ease;
        user-select: none;
    }

    .stars-rating .star.active {
        color: #F39C12;
    }

    .stars-rating .star:hover {
        transform: scale(1.2);
    }

    .oeuvre-select {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 12px;
    }

    .oeuvre-option {
        background: #F8F9FA;
        border: 2px solid #E8ECF1;
        border-radius: 12px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .oeuvre-option:hover {
        border-color: #6C3CE1;
        background: #F5F0FF;
    }

    .oeuvre-option.selected {
        border-color: #6C3CE1;
        background: #E8E0F8;
    }

    .oeuvre-option img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }

    .oeuvre-option .info .titre {
        font-weight: 500;
        color: #2D3436;
        font-size: 14px;
    }

    .oeuvre-option .info .artisan {
        font-size: 12px;
        color: #95A5A6;
    }

    @media (max-width: 768px) {
        .oeuvre-select {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-star text-primary mr-3"></i> Donner un avis
    </h1>

    <div class="card">
        <form action="{{ route('client.avis.store') }}" method="POST">
            @csrf

            <!-- ===== SÉLECTION DE L'ŒUVRE ===== -->
            <div class="form-group">
                <label class="form-label">Sélectionnez une œuvre <span style="color:#E74C3C;">*</span></label>
                <p style="font-size:13px;color:#95A5A6;margin-bottom:8px;">
                    Choisissez une œuvre que vous avez achetée et souhaitez évaluer.
                </p>

                @if($commandeId)
                    <input type="hidden" name="commande_id" value="{{ $commandeId }}">
                @endif

                <div class="oeuvre-select">
                    @foreach($oeuvres as $oeuvre)
                        <label class="oeuvre-option {{ old('oeuvre_id') == $oeuvre->id ? 'selected' : '' }}">
                            <input type="radio" name="oeuvre_id" value="{{ $oeuvre->id }}"
                                   {{ old('oeuvre_id', $oeuvreId) == $oeuvre->id ? 'checked' : '' }}
                                   style="display:none;">
                            <img src="{{ $oeuvre->image_principale ?? asset('images/placeholder.jpg') }}"
                                 alt="{{ $oeuvre->titre }}">
                            <div class="info">
                                <div class="titre">{{ $oeuvre->titre }}</div>
                                <div class="artisan">{{ $oeuvre->artisan->name ?? 'Artisan' }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @error('oeuvre_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror

                @if($oeuvres->isEmpty())
                    <p style="text-align:center;color:#95A5A6;padding:20px;">
                        <i class="fas fa-shopping-bag" style="font-size:30px;display:block;margin-bottom:8px;"></i>
                        Vous n'avez pas encore d'œuvres à évaluer.
                        <br><a href="{{ route('client.commandes.index') }}" class="text-primary">Voir mes commandes</a>
                    </p>
                @endif
            </div>

            <!-- ===== NOTE ===== -->
            <div class="form-group">
                <label class="form-label">Votre note <span style="color:#E74C3C;">*</span></label>
                <div class="stars-rating" id="starsRating">
                    <span class="star" data-value="1">★</span>
                    <span class="star" data-value="2">★</span>
                    <span class="star" data-value="3">★</span>
                    <span class="star" data-value="4">★</span>
                    <span class="star" data-value="5">★</span>
                </div>
                <input type="hidden" name="note" id="noteInput" value="{{ old('note', 5) }}" required>
                @error('note')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- ===== COMMENTAIRE ===== -->
            <div class="form-group">
                <label class="form-label" for="commentaire">Commentaire</label>
                <textarea id="commentaire" name="commentaire" class="form-control @error('commentaire') is-invalid @enderror"
                          rows="4" placeholder="Partagez votre expérience avec cette œuvre...">{{ old('commentaire') }}</textarea>
                @error('commentaire')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <p style="font-size:12px;color:#95A5A6;margin-top:4px;">Maximum 1000 caractères</p>
            </div>

            <!-- ===== BOUTONS ===== -->
            <div style="display:flex;gap:12px;padding-top:20px;border-top:2px solid #F0F0F0;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Envoyer mon avis
                </button>
                <a href="{{ route('client.avis.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    // ===== GESTION DES ÉTOILES =====
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star');
        const noteInput = document.getElementById('noteInput');

        function updateStars(value) {
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                if (starValue <= value) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
            noteInput.value = value;
        }

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                updateStars(value);
            });

            star.addEventListener('mouseenter', function() {
                const value = parseInt(this.dataset.value);
                stars.forEach(s => {
                    const sv = parseInt(s.dataset.value);
                    if (sv <= value) {
                        s.style.color = '#F39C12';
                    } else {
                        s.style.color = '#D4D4D4';
                    }
                });
            });

            star.addEventListener('mouseleave', function() {
                const currentValue = parseInt(noteInput.value) || 0;
                stars.forEach(s => {
                    const sv = parseInt(s.dataset.value);
                    if (sv <= currentValue) {
                        s.style.color = '';
                    } else {
                        s.style.color = '';
                    }
                });
            });
        });

        // Initialiser avec la valeur par défaut
        const initialValue = parseInt(noteInput.value) || 5;
        updateStars(initialValue);

        // ===== SÉLECTION D'ŒUVRE =====
        document.querySelectorAll('.oeuvre-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.oeuvre-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });
    });
</script>

@endsection