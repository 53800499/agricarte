@extends('layouts.auth')

@section('title', 'Inscription')

@section('sidebar')
<div class="w-100 text-center">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="auth-image rounded-circle ">
    </div>    <h3 class="my-4">Rejoignez Agricarte</h3>
    <p>Créez votre compte pour commencer à vendre vos produits agricoles en ligne.</p>
    <ul class="benefits-list mt-4">
        <li><i class="fas fa-store"></i> Créez votre boutique en ligne</li>
        <li><i class="fas fa-chart-line"></i> Suivez vos ventes</li>
        <li><i class="fas fa-users"></i> Développez votre clientèle</li>
        <li><i class="fas fa-map-marker-alt"></i> Gagnez en visibilité locale</li>
    </ul>
    <div class="mt-4">
        <p>Déjà inscrit ?</p>
        <a href="{{ route('login') }}" class="btn btn-outline-light">Se connecter</a>
    </div>
@endsection

@section('content')
    <h2 class="mb-4">Inscription</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Nom complet</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" placeholder="Veillez entrer votre nom" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="eskaydev@gmail.com"
                        name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Téléphone</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                        name="phone" value="{{ old('phone') }}" placeholder="+2290153800499" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="role" class="form-label">Type de compte</label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Client
                    </option>
                    <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Agriculteur
                    </option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="farmerFields" class="d-none">
                <div class="col-12 mb-3">
                    <label for="address" class="form-label">Adresse</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                            name="address" value="{{ old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror"
                            id="latitude" name="latitude" value="{{ old('latitude') }}">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror"
                            id="longitude" name="longitude" value="{{ old('longitude') }}">
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="********" id="password"
                        name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Confirmer le mot de
                    passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password_confirmation" placeholder="********" name="password_confirmation"
                        required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms"
                    name="terms" required>
                <label class="form-check-label" for="terms">
                    J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions
                        d'utilisation</a>
                </label>
                @error('terms')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success">S'inscrire</button>
            <a href="{{ route('login') }}" class="btn btn-link text-muted">Déjà inscrit ?
                Connectez-vous</a>
        </div>
    </form>

    <!-- Modal des conditions d'utilisation -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Conditions d'utilisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Acceptation des conditions</h6>
                    <p>En vous inscrivant sur Agricarte, vous acceptez les présentes conditions d'utilisation...</p>

                    <h6>2. Utilisation du service</h6>
                    <p>Le service est destiné à mettre en relation les agriculteurs et les consommateurs...</p>

                    <h6>3. Protection des données</h6>
                    <p>Vos données personnelles sont protégées conformément au RGPD...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('role').addEventListener('change', function() {
            const farmerFields = document.getElementById('farmerFields');
            if (this.value === 'farmer') {
                farmerFields.classList.remove('d-none');
                farmerFields.querySelectorAll('input').forEach(input => input.required = true);
            } else {
                farmerFields.classList.add('d-none');
                farmerFields.querySelectorAll('input').forEach(input => input.required = false);
            }
        });

        // Géolocalisation
        document.getElementById('address').addEventListener('blur', function() {
            if (this.value && document.getElementById('role').value === 'farmer') {
                // Utiliser l'API de géocodage pour obtenir les coordonnées
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.value)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            document.getElementById('latitude').value = data[0].lat;
                            document.getElementById('longitude').value = data[0].lon;
                        }
                    });
            }
        });
    </script>
@endsection
