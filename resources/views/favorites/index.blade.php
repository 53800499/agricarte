@extends('layouts.app')

@section('title', 'Mes Favoris')
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
    </style>
@endpush
@section('content')
<!-- Hero Section -->
    <section class="producer-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Mes Favoris</h1>
                    <p class="lead">Retrouvez ici tous vos produits favoris
                    </p>
                </div>
            </div>
        </div>
    </section>
<div class="container py-5">

    @if($favorites->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-heart fa-4x text-muted mb-4"></i>
            <h3 class="h4">Vous n'avez pas encore de favoris</h3>
            <p class="text-muted">Découvrez nos produits et ajoutez-les à vos favoris</p>
            <a href="{{ route('products') }}" class="btn btn-success mt-3">Découvrir les produits</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($favorites as $product)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/products/default.jpg') }}"
                                class="card-img-top" alt="{{ $product->name }}"
                                style="height: 200px; object-fit: cover;">
                            <div class="product-overlay">
                                <button class="btn btn-success btn-sm favorite-toggle"
                                        data-product-id="{{ $product->id }}"
                                        data-url="{{ route('favorites.toggle', $product) }}">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-success fw-bold">{{ number_format($product->price, 2) }}FCFA / {{ $product->unit }}</p>
                            <p class="text-muted small">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $product->user->address }}
                            </p>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success btn-sm w-100">
                                Voir le produit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $favorites->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .product-card:hover .product-overlay {
        opacity: 1;
    }
    .favorite-toggle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const favoriteButtons = document.querySelectorAll('.favorite-toggle');

        favoriteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.dataset.productId;
                const url = this.dataset.url;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'removed') {
                        // Supprimer la carte du produit des favoris
                        const productCard = this.closest('.col-md-4');
                        productCard.remove();

                        // Vérifier s'il reste des produits
                        if (document.querySelectorAll('.col-md-4').length === 0) {
                            location.reload();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endpush
