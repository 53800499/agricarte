@extends('layouts.auth')

@section('title', 'Connexion')

@section('sidebar')
    <div class="w-100 text-center">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="auth-image rounded-circle ">
    </div>
    <h3>Bienvenue sur Agricarte</h3>
    <p>Connectez-vous pour accéder à votre espace et gérer vos produits agricoles.</p>
    <ul class="features-list mt-4">
        <li><i class="fas fa-check-circle"></i> Gestion simplifiée de vos produits</li>
        <li><i class="fas fa-check-circle"></i> Visibilité accrue auprès des clients</li>
        <li><i class="fas fa-check-circle"></i> Statistiques détaillées</li>
        <li><i class="fas fa-check-circle"></i> Support client dédié</li>
    </ul>
    <div class="mt-4">
        <p>Pas encore inscrit ?</p>
        <a href="{{ route('register') }}" class="btn btn-outline-light">Créer un compte</a>
    </div>
@endsection

@section('content')
    <h2 class="mb-4">Connexion</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" placeholder="eskay_dev@gmail.com" name="email" value="{{ old('email') }}" required autofocus>
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" placeholder="********" class="form-control" id="password" name="password" required>
            </div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Se souvenir de moi</label>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success">Se connecter</button>
        </div>
        <div class="text-center mt-3">
            {{-- Temporairement commenté jusqu'à l'implémentation de la réinitialisation du mot de passe --}}
            {{-- <a href="{{ route('password.request') }}" class="text-muted">Mot de passe oublié ?</a> --}}
            <a href="#" class="text-muted" onclick="alert('Cette fonctionnalité sera bientôt disponible.')">Mot de passe oublié ?</a>
        </div>
    </form>
@endsection
