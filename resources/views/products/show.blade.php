@extends('layouts.app')

@section('title', $product->name . ' - AgriCarte')

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

    .product-gallery {
        margin-top: 2em;
    }

    .main-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .thumbnail:hover {
        transform: scale(1.05);
        border-color: var(--primary-color);
    }

    .product-info {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .product-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 15px;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .product-category {
        background: var(--primary-color);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .product-rating {
        color: var(--warning-color);
        font-size: 1rem;
    }

    .product-reviews {
        color: var(--gray-color);
        font-size: 0.9rem;
    }

    .product-price {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .product-description {
        color: var(--gray-color);
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .product-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--gray-color);
    }

    .stat-item i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .quantity-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--light-color);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--dark-color);
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: var(--primary-color);
        color: white;
    }

    .quantity-input {
        width: 60px;
        text-align: center;
        border: none;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .product-actions {
        display: flex;
        gap: 15px;
    }

    .btn-add-cart {
        flex: 1;
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-add-cart:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .btn-favorite {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: var(--light-color);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-color);
        transition: all 0.3s ease;
    }

    .btn-favorite:hover {
        background: var(--danger-color);
        color: white;
    }

    .producer-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .producer-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .producer-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .producer-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 5px;
    }

    .producer-location {
        color: var(--gray-color);
        font-size: 0.9rem;
    }

    .producer-contact {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }

    .contact-btn {
        flex: 1;
        padding: 10px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-call {
        background: var(--primary-color);
        color: white;
    }

    .btn-message {
        background: var(--light-color);
        color: var(--dark-color);
    }

    .contact-btn:hover {
        transform: translateY(-2px);
    }

    .map-container {
        height: 200px;
        border-radius: 15px;
        overflow: hidden;
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="product-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">{{ $product->name }}</h1>
                    <p class="lead">{{ $product->category->name }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <!-- Product Gallery -->
            <div class="col-lg-8">
                <div class="product-gallery">
                    <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/default-product.jpg') }}"
                         alt="{{ $product->name }}"
                         class="main-image mb-3">
                    <div class="d-flex gap-2">
                        @if($product->images)
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->path) }}"
                                     alt="{{ $product->name }}"
                                     class="thumbnail">
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-4">
                <div class="product-info">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <div class="product-meta">
                        <span class="product-category">{{ $product->category->name }}</span>
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
                    <p class="product-description">{{ $product->description }}</p>
                    <div class="product-stats">
                        <div class="stat-item">
                            <i class="fas fa-box"></i>
                            <span>{{ $product->stock_quantity }} en stock</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-truck"></i>
                            <span>Livraison gratuite</span>
                        </div>
                    </div>
                    <div class="quantity-control">
                        <button class="quantity-btn" id="decrease">-</button>
                        <input type="number" class="quantity-input" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}">
                        <button class="quantity-btn" id="increase">+</button>
                    </div>
                    <div class="product-actions">
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </form>
                        <button class="btn-favorite">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>

                <!-- Producer Info -->
                <div class="producer-card mt-4">
                    <div class="producer-header">
                        <img src="{{ $product->user->profile_image ? asset('storage/' . $product->user->profile_image) : asset('images/default-farmer.jpg') }}"
                             alt="{{ $product->user->name }}"
                             class="producer-avatar">
                        <div>
                            <h3 class="producer-name">{{ $product->user->name }}</h3>
                            <p class="producer-location">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $product->user->address }}
                            </p>
                        </div>
                    </div>
                    <p class="text-muted">{{ $product->user->description }}</p>
                    <div class="producer-contact">
                        <a href="tel:{{ $product->user->phone }}" class="btn contact-btn btn-call">
                            <i class="fas fa-phone me-2"></i>Appeler
                        </a>
                        <a href="mailto:{{ $product->user->email }}" class="btn contact-btn btn-message">
                            <i class="fas fa-envelope me-2"></i>Message
                        </a>
                    </div>
                    <div class="map-container" id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const decreaseBtn = document.getElementById('decrease');
        const increaseBtn = document.getElementById('increase');
        const quantityInput = document.querySelector('.quantity-input');
        const hiddenQuantityInput = document.getElementById('quantity-input');
        const maxQuantity = {{ $product->stock_quantity }};

        decreaseBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                value--;
                quantityInput.value = value;
                hiddenQuantityInput.value = value;
            }
        });

        increaseBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value < maxQuantity) {
                value++;
                quantityInput.value = value;
                hiddenQuantityInput.value = value;
            }
        });

        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (value < 1) value = 1;
            if (value > maxQuantity) value = maxQuantity;
            this.value = value;
            hiddenQuantityInput.value = value;
        });

        // Initialize map
        const map = L.map('map').setView([{{ $product->user->latitude }}, {{ $product->user->longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([{{ $product->user->latitude }}, {{ $product->user->longitude }}], {
            icon: L.divIcon({
                className: 'producer-marker',
                html: '<i class="fas fa-map-marker-alt fa-2x text-primary"></i>'
            })
        }).addTo(map);

        marker.bindPopup(`
            <div class="p-2">
                <h6>{{ $product->user->name }}</h6>
                <p class="mb-0">{{ $product->user->address }}</p>
            </div>
        `);

        // Image gallery
        const mainImage = document.querySelector('.main-image');
        const thumbnails = document.querySelectorAll('.thumbnail');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', () => {
                mainImage.src = thumbnail.src;
            });
        });
    });
</script>
@endpush
