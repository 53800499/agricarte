@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Détails de l'utilisateur</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Détails</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <!-- Carte de profil -->
                <div class="card shadow mb-4">
                    <div class="card-body text-center">
                        <div class="position-relative d-inline-block mb-3">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}"
                                     alt="Photo de profil"
                                     class="rounded-circle"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-gray-400"></i>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0">
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'farmer' ? 'success' : 'primary') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        <p class="small text-muted mb-0">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Membre depuis {{ $user->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                <!-- Statut du compte -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-shield me-2"></i>Statut du compte
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" disabled
                                       {{ $user->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ $user->is_active ? 'Compte actif' : 'Compte inactif' }}
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rôle</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'farmer' ? 'tractor' : 'user') }} me-2"></i>
                                <span>{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Informations personnelles -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user me-2"></i>Informations personnelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom complet</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user me-2"></i>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope me-2"></i>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone me-2"></i>
                                    <span>{{ $user->phone ?? 'Non renseigné' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-map-marker-alt me-2"></i>Adresse
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adresse</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-home me-2"></i>
                                    <span>{{ $user->address ?? 'Non renseignée' }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ville</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-city me-2"></i>
                                    <span>{{ $user->city ?? 'Non renseignée' }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Code postal</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-mail-bulk me-2"></i>
                                    <span>{{ $user->postal_code ?? 'Non renseigné' }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pays</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-globe me-2"></i>
                                    <span>{{ $user->country ?? 'Non renseigné' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->role === 'farmer')
                <!-- Informations du producteur -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tractor me-2"></i>Informations du producteur
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description</label>
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-align-left me-2 mt-1"></i>
                                    <span>{{ $user->description ?? 'Non renseignée' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marked-alt me-2"></i>
                                    <span>{{ $user->latitude ?? 'Non renseignée' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marked-alt me-2"></i>
                                    <span>{{ $user->longitude ?? 'Non renseignée' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
