@extends('layouts.app')

@section('title', $producer->name . ' - AgriCarte')

@push('styles')
<style>
    .producer-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slide1.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -76px;
    }

    .producer-profile {
        margin-top: -100px;
    }

    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        object-fit: cover;
    }

    .product-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        height: 250px;
        object-fit: cover;
        border-radius: 15px 15px 0 0;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 15px;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .product-actions {
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }

    .product-card:hover .product-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .category-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary-color);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        z-index: 2;
    }

    .stock-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        background: rgba(255, 255, 255, 0.9);
        z-index: 2;
    }

    .stock-badge.in-stock {
        color: var(--success-color);
    }

    .stock-badge.out-stock {
        color: var(--danger-color);
    }

    .product-details {
        padding: 20px;
        background: white;
    }

    .product-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark-color);
    }

    .product-description {
        color: var(--gray-color);
        font-size: 0.9rem;
        margin-bottom: 15px;
        line-height: 1.5;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .product-rating {
        color: var(--warning-color);
        font-size: 0.9rem;
    }

    .product-reviews {
        color: var(--gray-color);
        font-size: 0.9rem;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .unit-badge {
        background: var(--light-color);
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.8rem;
        color: var(--gray-color);
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border-color);
    }

    .product-stats {
        display: flex;
        gap: 15px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--gray-color);
        font-size: 0.9rem;
    }

    .stat-item i {
        color: var(--primary-color);
    }

    .contact-info {
        background: var(--light-color);
        border-radius: 15px;
        padding: 20px;
    }

    .contact-info i {
        width: 20px;
        color: var(--primary-color);
    }

    .map-container {
        height: 300px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .description-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="producer-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">{{ $producer->name }}</h1>
                    <p class="lead">{{ $producer->address }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Profile Section -->
        <div class="producer-profile mb-5">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <img src="{{ $producer->profile_image ? asset('storage/' . $producer->profile_image) : asset('images/default-farmer.jpg') }}"
                                 alt="{{ $producer->name }}"
                                 class="profile-image me-4">
                            <div>
                                <h2 class="mb-3">{{ $producer->name }}</h2>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    {{ $producer->address }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-phone me-2"></i>
                                    {{ $producer->phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-info">
                        <h4 class="mb-4">Contact</h4>
                        <p><i class="fas fa-envelope me-2"></i> {{ $producer->email }}</p>
                        <p><i class="fas fa-phone me-2"></i> {{ $producer->phone }}</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i> {{ $producer->address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <div class="description-card">
                    <h3 class="mb-4">À propos</h3>
                    <p>{{ $producer->description }}</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="map-container" id="map"></div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">Produits disponibles</h3>
            </div>
            @foreach($producer->products as $product)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="product-card">
                        <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/default-product.jpg') }}"
                             alt="{{ $product->name }}"
                             class="product-image w-100">
                        <div class="product-overlay"></div>
                        <span class="category-badge">{{ $product->category->name }}</span>
                        <span class="stock-badge {{ $product->stock_quantity > 0 ? 'in-stock' : 'out-stock' }}">
                            {{ $product->stock_quantity > 0 ? 'En stock' : 'Rupture de stock' }}
                        </span>
                        <div class="product-actions">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">
                                <i class="fas fa-eye me-2"></i>Voir détails
                            </a>
                        </div>
                        <div class="product-details">
                            <h5 class="product-title">{{ $product->name }}</h5>
                            <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                            <div class="product-meta">
                                <div class="product-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="product-reviews">(12 avis)</span>
                            </div>
                            <div class="product-price">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                <span class="unit-badge">/ {{ $product->unit }}</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>{{ $product->stock_quantity }} en stock</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-truck"></i>
                                        <span>Livraison gratuite</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([{{ $producer->latitude }}, {{ $producer->longitude }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([{{ $producer->latitude }}, {{ $producer->longitude }}], {
            icon: L.divIcon({
                className: 'producer-marker',
                html: '<i class="fas fa-map-marker-alt fa-2x text-primary"></i>'
            })
        }).addTo(map);

        marker.bindPopup(`
            <div class="p-2">
                <h6>{{ $producer->name }}</h6>
                <p class="mb-0">{{ $producer->address }}</p>
            </div>
        `);
    });
</script>
@endpush
