@extends('layouts.app')

@section('title', 'Paramètres du compte')
@push('styles')
    <style>
        .product-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slide1.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -76px;
    }
        @media (max-width: 768px) {
        .product-hero {
            min-height: 200px;
        }
    }
    </style>
@endpush
@section('content')
<!-- Hero Section -->
    <section class="product-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Mon profile</h1>
                    <p class="lead animate__animated animate__fadeInUp">Tous ce qui me concerne</p>
                </div>
            </div>
        </div>
    </section>
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ $user->profile_image_url }}" alt="Photo de profil"
                             class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small">{{ $user->email }}</p>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="tab">
                            <i class="fas fa-user me-2"></i> Profil
                        </a>
                        <a href="#password" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                            <i class="fas fa-lock me-2"></i> Mot de passe
                        </a>
                        <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                            <i class="fas fa-bell me-2"></i> Notifications
                        </a>
                        <a href="#privacy" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                            <i class="fas fa-shield-alt me-2"></i> Confidentialité
                        </a>
                        <a href="#delete" class="list-group-item list-group-item-action text-danger" data-bs-toggle="tab">
                            <i class="fas fa-trash-alt me-2"></i> Supprimer le compte
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Onglet Profil -->
                <div class="tab-pane fade show active" id="profile">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Informations du profil</h5>
                            <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $user->description) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Photo de profil</label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Onglet Mot de passe -->
                <div class="tab-pane fade" id="password">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Changer le mot de passe</h5>
                            <form action="{{ route('settings.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-success">Mettre à jour le mot de passe</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Onglet Notifications -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Préférences de notification</h5>
                            <form action="{{ route('settings.notifications.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications"
                                               {{ old('email_notifications', $user->settings['notifications']['email_notifications'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">Notifications par email</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="push_notifications" name="push_notifications"
                                               {{ old('push_notifications', $user->settings['notifications']['push_notifications'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="push_notifications">Notifications push</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="marketing_emails" name="marketing_emails"
                                               {{ old('marketing_emails', $user->settings['notifications']['marketing_emails'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="marketing_emails">Emails marketing</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Enregistrer les préférences</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Onglet Confidentialité -->
                <div class="tab-pane fade" id="privacy">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Paramètres de confidentialité</h5>
                            <form action="{{ route('settings.privacy.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="show_email" name="show_email"
                                               {{ old('show_email', $user->settings['privacy']['show_email'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_email">Afficher mon email</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="show_phone" name="show_phone"
                                               {{ old('show_phone', $user->settings['privacy']['show_phone'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_phone">Afficher mon numéro de téléphone</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="show_address" name="show_address"
                                               {{ old('show_address', $user->settings['privacy']['show_address'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_address">Afficher mon adresse</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Enregistrer les paramètres</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Onglet Suppression -->
                <div class="tab-pane fade" id="delete">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Supprimer le compte</h5>
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">Attention !</h6>
                                <p class="mb-0">La suppression de votre compte est irréversible. Toutes vos données seront définitivement supprimées.</p>
                            </div>
                            <form action="{{ route('settings.destroy') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')
                                <div class="mb-3">
                                    <label for="delete_password" class="form-label">Confirmez votre mot de passe</label>
                                    <input type="password" class="form-control" id="delete_password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .list-group-item.active {
        background-color: #28a745;
        border-color: #28a745;
    }
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }
</style>
@endpush
