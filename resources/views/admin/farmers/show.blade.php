@extends('layouts.admin')

@section('title', 'Détails de l\'Agriculteur')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Détails de l'Agriculteur</h1>
            <div>
                <a href="{{ route('admin.farmers.edit', $farmer) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('admin.farmers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informations personnelles -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-body text-center">
                        @if($farmer->profile_image)
                            <img src="{{ asset('storage/' . $farmer->profile_image) }}"
                                 alt="{{ $farmer->name }}"
                                 class="img-thumbnail rounded-circle mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-4x text-white"></i>
                            </div>
                        @endif

                        <h4 class="mb-0">{{ $farmer->name }}</h4>
                        <p class="text-muted mb-3">{{ $farmer->email }}</p>

                        <div class="d-flex justify-content-center mb-3">
                            <span class="badge {{ $farmer->is_active ? 'bg-success' : 'bg-danger' }} me-2">
                                {{ $farmer->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            <span class="badge bg-primary">Agriculteur</span>
                        </div>

                        <hr>

                        <div class="text-start">
                            <p class="mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>
                                {{ $farmer->phone ?? 'Non renseigné' }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                {{ $farmer->address }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-city text-primary me-2"></i>
                                {{ $farmer->city }}, {{ $farmer->postal_code }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-globe text-primary me-2"></i>
                                {{ $farmer->country }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="mb-0">{{ $farmer->products_count ?? 0 }}</h4>
                                <small class="text-muted">Produits</small>
                            </div>
                            <div class="col-4">
                                <h4 class="mb-0">{{ $farmer->orders_count ?? 0 }}</h4>
                                <small class="text-muted">Commandes</small>
                            </div>
                            <div class="col-4">
                                <h4 class="mb-0">{{ $farmer->revenue ?? '0 €' }}</h4>
                                <small class="text-muted">Revenus</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description et localisation -->
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Description</h6>
                    </div>
                    <div class="card-body">
                        {{ $farmer->description ?? 'Aucune description disponible.' }}
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Localisation</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Latitude:</strong></p>
                                <p>{{ $farmer->latitude }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Longitude:</strong></p>
                                <p>{{ $farmer->longitude }}</p>
                            </div>
                        </div>
                        <div id="map" style="height: 300px;"></div>
                    </div>
                </div>

                <!-- Derniers produits -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Derniers produits</h6>
                        <a href="{{ route('admin.products.index', ['farmer_id' => $farmer->id]) }}" class="btn btn-sm btn-primary">
                            Voir tous les produits
                        </a>
                    </div>
                    <div class="card-body">
                        @if($farmer->products && $farmer->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Prix</th>
                                            <th>Stock</th>
                                            <th>Vente</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($farmer->products->take(5) as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->price }} €</td>
                                                <td>{{ $product->stock_quantity }}</td>
                                                <td>
                                                    <span class="badge {{ $product->is_featured ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted mb-0">Aucun produit disponible</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([{{ $farmer->latitude }}, {{ $farmer->longitude }}], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([{{ $farmer->latitude }}, {{ $farmer->longitude }}])
        .addTo(map)
        .bindPopup("{{ $farmer->name }}<br>{{ $farmer->address }}")
        .openPopup();
});
</script>
@endpush
