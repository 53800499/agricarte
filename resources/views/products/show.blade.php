@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 400px;
        border-radius: 10px;
    }
    .product-image {
        max-height: 400px;
        width: 100%;
        object-fit: cover;
        border-radius: 10px;
    }
    .farmer-card {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .farmer-card:hover {
        transform: translateY(-5px);
    }
    .farmer-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #4CAF50;
    }
    .badge-stock {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    .contact-btn {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produits</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Colonne de gauche : Image et détails du produit -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="product-image mb-4">

                    <h1 class="h2 mb-4">{{ $product->name }}</h1>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <span class="badge bg-primary me-2">{{ $product->category->name }}</span>
                            @if($product->stock_quantity > 0)
                                <span class="badge bg-success badge-stock">
                                    En stock : {{ $product->stock_quantity }} {{ $product->unit }}
                                </span>
                            @else
                                <span class="badge bg-danger badge-stock">Rupture de stock</span>
                            @endif
                        </div>
                        <h3 class="text-primary mb-0">{{ number_format($product->price, 2, ',', ' ') }} €</h3>
                    </div>

                    <div class="mb-4">
                        <h4>Description</h4>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>

                    @if($product->stock_quantity > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            <div class="input-group me-3" style="max-width: 200px;">
                                <span class="input-group-text">Quantité</span>
                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock_quantity }}">
                                <span class="input-group-text">{{ $product->unit }}</span>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Ajouter au panier
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne de droite : Informations du producteur et carte -->
        <div class="col-lg-4">
            <!-- Carte du producteur -->
            <div class="card farmer-card mb-4">
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $product->user->profile_image) }}"
                         alt="{{ $product->user->name }}"
                         class="farmer-image mb-3">

                    <h4>{{ $product->user->name }}</h4>
                    <p class="text-muted mb-3">Producteur local</p>

                    <div class="d-grid gap-2">
                        <a href="tel:{{ $product->user->phone }}" class="btn btn-outline-primary contact-btn">
                            <i class="fas fa-phone me-2"></i>{{ $product->user->phone }}
                        </a>
                        <a href="mailto:{{ $product->user->email }}" class="btn btn-outline-primary contact-btn">
                            <i class="fas fa-envelope me-2"></i>Contacter par email
                        </a>
                    </div>

                    @if($product->user->description)
                        <div class="mt-3">
                            <h5>À propos</h5>
                            <p class="text-muted">{{ $product->user->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Carte de localisation -->
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Localisation</h4>
                    <div id="map"></div>
                    <p class="mt-3 mb-0">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        {{ $product->user->address }}, {{ $product->user->postal_code }} {{ $product->user->city }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation de la carte
        const map = L.map('map').setView([{{ $product->user->latitude }}, {{ $product->user->longitude }}], 13);

        // Ajout de la couche OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Ajout du marqueur pour la position du producteur
        L.marker([{{ $product->user->latitude }}, {{ $product->user->longitude }}])
            .addTo(map)
            .bindPopup("{{ $product->user->name }}<br>{{ $product->user->address }}")
            .openPopup();

        // Si la géolocalisation est disponible, on peut ajouter la position de l'utilisateur
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                // Ajout du marqueur pour la position de l'utilisateur
                L.marker([userLat, userLng], {
                    icon: L.divIcon({
                        className: 'user-marker',
                        html: '<i class="fas fa-user-circle fa-2x text-primary"></i>'
                    })
                }).addTo(map)
                .bindPopup("Votre position")
                .openPopup();

                // Ajustement de la vue pour montrer les deux marqueurs
                const bounds = L.latLngBounds(
                    [userLat, userLng],
                    [{{ $product->user->latitude }}, {{ $product->user->longitude }}]
                );
                map.fitBounds(bounds, { padding: [50, 50] });
            });
        }
    });
</script>
@endpush
