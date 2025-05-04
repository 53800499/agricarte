@extends('layouts.auth')

@section('title', 'Connexion')

@section('sidebar')
    <div class="w-100 text-center animate__animated animate__fadeIn">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="auth-image rounded-circle">
        <h3 class="animate__animated animate__fadeInUp">Bienvenue sur Agricarte</h3>
        <p class="animate__animated animate__fadeInUp animate__delay-1s">Connectez-vous pour accéder à votre espace et gérer vos produits agricoles.</p>
    </div>
    <ul class="features-list mt-4">
        <li class="animate__animated animate__fadeInLeft animate__delay-1s">
            <i class="fas fa-leaf"></i> Gestion simplifiée de vos produits
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-2s">
            <i class="fas fa-users"></i> Visibilité accrue auprès des clients
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-3s">
            <i class="fas fa-chart-line"></i> Statistiques détaillées
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-4s">
            <i class="fas fa-headset"></i> Support client dédié
        </li>
    </ul>
    <div class="mt-4 text-center animate__animated animate__fadeInUp animate__delay-5s">
        <p class="mb-3">Pas encore inscrit ?</p>
        <a href="{{ route('register') }}" class="btn btn-outline-light w-100">
            <i class="fas fa-user-plus me-2"></i>Créer un compte
        </a>
    </div>
@endsection

@section('content')
    <div class="animate__animated animate__fadeIn">
        <h2 class="mb-4 text-center">Connexion à votre compte</h2>

        @if($errors->any())
            <div class="alert alert-danger animate__animated animate__shakeX">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="eskay_dev@gmail.com"
                           required
                           autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label">Mot de passe</label>
                    <a href="#" class="text-muted small text-decoration-none" onclick="alert('Cette fonctionnalité sera bientôt disponible.')">
                        <i class="fas fa-question-circle"></i> Mot de passe oublié ?
                    </a>
                </div>
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

            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label user-select-none" for="remember">
                        <i class="fas fa-clock text-muted me-1"></i>Se souvenir de moi
                    </label>
                </div>
            </div>

            <div class="d-grid gap-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                </button>
                <a href="{{ route('register') }}" class="btn btn-light">
                    <i class="fas fa-user-plus me-2"></i>Créer un compte
                </a>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted small">
                En vous connectant, vous acceptez nos
                <a href="#" class="text-decoration-none">conditions d'utilisation</a> et notre
                <a href="#" class="text-decoration-none">politique de confidentialité</a>.
            </p>
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
</script>
@endsection
