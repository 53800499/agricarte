@extends('layouts.auth')

@section('title', 'Inscription')

@section('sidebar')
    <div class="w-100 text-center animate__animated animate__fadeIn">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="auth-image rounded-circle">
        <h3 class="animate__animated animate__fadeInUp">Rejoignez Agricarte</h3>
        <p class="animate__animated animate__fadeInUp animate__delay-1s">Créez votre compte pour commencer à vendre vos produits agricoles en ligne.</p>
    </div>
    <ul class="benefits-list mt-4">
        <li class="animate__animated animate__fadeInLeft animate__delay-1s">
            <i class="fas fa-store"></i> Créez votre boutique en ligne
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-2s">
            <i class="fas fa-chart-line"></i> Suivez vos ventes
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-3s">
            <i class="fas fa-users"></i> Développez votre clientèle
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-4s">
            <i class="fas fa-map-marker-alt"></i> Gagnez en visibilité locale
        </li>
    </ul>
    <div class="mt-4 text-center animate__animated animate__fadeInUp animate__delay-5s">
        <p class="mb-3">Déjà inscrit ?</p>
        <a href="{{ route('login') }}" class="btn btn-outline-light w-100">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
        </a>
    </div>
@endsection

@section('content')
    <div class="animate__animated animate__fadeIn">
        <h2 class="mb-4 text-center">Créer votre compte</h2>

        @if ($errors->any())
            <div class="alert alert-danger animate__animated animate__shakeX">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
            @csrf
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="name" class="form-label">Nom complet</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-user text-muted"></i>
                        </span>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Votre nom complet"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="eskaydev@gmail.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="phone" class="form-label">Téléphone</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-phone text-muted"></i>
                        </span>
                        <input type="tel"
                               class="form-control @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               placeholder="+2290153800499"
                               required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="role" class="form-label">Type de compte</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-user-tag text-muted"></i>
                        </span>
                        <select class="form-select @error('role') is-invalid @enderror"
                                id="role"
                                name="role"
                                required>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Client</option>
                            <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Producteur</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="farmerFields" class="animate__animated animate__fadeIn d-none">
                <div class="mb-4">
                    <label for="address" class="form-label">Adresse</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-map-marker-alt text-muted"></i>
                        </span>
                        <input type="text"
                               class="form-control @error('address') is-invalid @enderror"
                               id="address"
                               name="address"
                               value="{{ old('address') }}"
                               placeholder="Votre adresse complète">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="latitude" class="form-label">Latitude</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-map text-muted"></i>
                            </span>
                            <input type="number"
                                   step="any"
                                   class="form-control @error('latitude') is-invalid @enderror"
                                   id="latitude"
                                   name="latitude"
                                   value="{{ old('latitude') }}"
                                   placeholder="Ex: 6.3702928">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="longitude" class="form-label">Longitude</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-map text-muted"></i>
                            </span>
                            <input type="number"
                                   step="any"
                                   class="form-control @error('longitude') is-invalid @enderror"
                                   id="longitude"
                                   name="longitude"
                                   value="{{ old('longitude') }}"
                                   placeholder="Ex: 2.3912362">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        L'adresse sera automatiquement convertie en coordonnées GPS lorsque vous la saisissez.
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="********"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="********"
                               required>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input @error('terms') is-invalid @enderror"
                           type="checkbox"
                           id="terms"
                           name="terms"
                           required>
                    <label class="form-check-label user-select-none" for="terms">
                        <i class="fas fa-check-circle text-muted me-1"></i>
                        J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions d'utilisation</a>
                    </label>
                    @error('terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid gap-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-user-plus me-2"></i>Créer mon compte
                </button>
                <a href="{{ route('login') }}" class="btn btn-light">
                    <i class="fas fa-sign-in-alt me-2"></i>Déjà inscrit ? Connectez-vous
                </a>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted small">
                En créant un compte, vous acceptez nos
                <a href="#" class="text-decoration-none">conditions d'utilisation</a> et notre
                <a href="#" class="text-decoration-none">politique de confidentialité</a>.
            </p>
        </div>
    </div>

    <!-- Modal des conditions d'utilisation -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-contract me-2"></i>
                        Conditions d'utilisation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6><i class="fas fa-check me-2"></i>1. Acceptation des conditions</h6>
                        <p>En vous inscrivant sur Agricarte, vous acceptez les présentes conditions d'utilisation et vous engagez à les respecter. Ces conditions régissent votre utilisation de la plateforme et peuvent être modifiées à tout moment.</p>
                    </div>

                    <div class="mb-4">
                        <h6><i class="fas fa-handshake me-2"></i>2. Utilisation du service</h6>
                        <p>Le service est destiné à mettre en relation les producteurs et les consommateurs. Vous vous engagez à fournir des informations exactes et à maintenir votre compte à jour. Tout usage frauduleux ou abusif pourra entraîner la suspension de votre compte.</p>
                    </div>

                    <div class="mb-4">
                        <h6><i class="fas fa-shield-alt me-2"></i>3. Protection des données</h6>
                        <p>Vos données personnelles sont protégées conformément au RGPD. Nous nous engageons à ne pas les partager avec des tiers sans votre consentement explicite. Vous disposez d'un droit d'accès, de modification et de suppression de vos données.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Validation du formulaire côté client
    (function () {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Gestion de l'affichage des champs pour les prod
    document.getElementById('role').addEventListener('change', function() {
        const farmerFields = document.getElementById('farmerFields')
        if (this.value === 'farmer') {
            farmerFields.classList.remove('d-none')
            farmerFields.querySelectorAll('input').forEach(input => input.required = true)
        } else {
            farmerFields.classList.add('d-none')
            farmerFields.querySelectorAll('input').forEach(input => input.required = false)
        }
    })

    // Géolocalisation automatique
    document.getElementById('address').addEventListener('blur', function() {
        if (this.value && document.getElementById('role').value === 'farmer') {
            const loadingIndicator = document.createElement('div')
            loadingIndicator.className = 'position-absolute end-0 top-50 translate-middle-y me-3'
            loadingIndicator.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>'
            this.parentElement.appendChild(loadingIndicator)

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.value)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        document.getElementById('latitude').value = data[0].lat
                        document.getElementById('longitude').value = data[0].lon
                        loadingIndicator.innerHTML = '<i class="fas fa-check text-success"></i>'
                        setTimeout(() => loadingIndicator.remove(), 2000)
                    } else {
                        loadingIndicator.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i>'
                        setTimeout(() => loadingIndicator.remove(), 2000)
                    }
                })
                .catch(() => {
                    loadingIndicator.innerHTML = '<i class="fas fa-times text-danger"></i>'
                    setTimeout(() => loadingIndicator.remove(), 2000)
                })
        }
    })
</script>
@endsection
