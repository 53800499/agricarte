@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Détails de l'utilisateur</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informations de base -->
            <div class="col-xl-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informations personnelles</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}"
                                     alt="{{ $user->name }}"
                                     class="img-profile rounded-circle"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Nom complet</label>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Email</label>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Téléphone</label>
                            <p>{{ $user->phone ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Rôle</label>
                            <p>
                                <span class="badge
                                    @switch($user->role)
                                        @case('admin')
                                            bg-danger
                                            @break
                                        @case('farmer')
                                            bg-success
                                            @break
                                        @default
                                            bg-primary
                                    @endswitch
                                ">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Statut</label>
                            <p>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="col-xl-8">
                <!-- Adresse -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Adresse</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-bold">Adresse</label>
                            <p>{{ $user->address ?? 'Non renseignée' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Ville</label>
                            <p>{{ $user->city ?? 'Non renseignée' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Code postal</label>
                            <p>{{ $user->postal_code ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Pays</label>
                            <p>{{ $user->country ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informations du producteur -->
                @if($user->role === 'farmer')
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Informations du producteur</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-bold">Description</label>
                                <p>{{ $user->description ?? 'Non renseignée' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Coordonnées GPS</label>
                                <p>
                                    @if($user->latitude && $user->longitude)
                                        <a href="https://www.google.com/maps?q={{ $user->latitude }},{{ $user->longitude }}"
                                           target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-map-marker-alt"></i> Voir sur la carte
                                        </a>
                                    @else
                                        Non renseignées
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Dernières commandes -->
                @if($user->orders->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Dernières commandes</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>N° Commande</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->orders->take(5) as $order)
                                            <tr>
                                                <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                                <td>
                                                    <span class="badge
                                                        @switch($order->status)
                                                            @case('completed')
                                                                bg-success
                                                                @break
                                                            @case('processing')
                                                                bg-info
                                                                @break
                                                            @case('pending')
                                                                bg-warning
                                                                @break
                                                            @case('cancelled')
                                                                bg-danger
                                                                @break
                                                        @endswitch
                                                    ">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
