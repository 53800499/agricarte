@extends('layouts.app')

@section('title', $producer->name . ' - AgriCarte')

@push('styles')
<style>
    .producer-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ $producer->profile_image ? asset('storage/' . $producer->profile_image) : asset('images/slide1.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -76px;
    }

    .producer-info {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-top: -50px;
    }

    .producer-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .product-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .info-item i {
        width: 30px;
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .producer-hero {
            min-height: 200px;
        }

        .producer-image {
            width: 100px;
            height: 100px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="producer-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">{{ $producer->name }}</h1>
                    <p class="lead animate__animated animate__fadeInUp">{{ $producer->description }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Producer Info -->
        <div class="producer-info animate__animated animate__fadeInUp">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="{{ $producer->profile_image ? asset('storage/' . $producer->profile_image) : asset('images/default-farmer.jpg') }}"
                        class="producer-image mb-3" alt="{{ $producer->name }}">
                </div>
                <div class="col-md-9">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $producer->address }}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <span>{{ $producer->phone }}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $producer->email }}</span>
                    </div>
                    @if($producer->website)
                        <div class="info-item">
                            <i class="fas fa-globe"></i>
                            <a href="{{ $producer->website }}" target="_blank">{{ $producer->website }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4 animate__animated animate__fadeInLeft">Produits</h2>
            </div>
            @forelse($producer->products as $product)
                <div class="col-md-4 mb-4 animate__animated animate__fadeInUp">
                    <div class="product-card">
                        <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->path) : asset('images/default-product.jpg') }}"
                            class="product-image w-100" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ $product->category->name }}</p>
                            <p class="card-text">{{ $product->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">{{ number_format($product->price, 2) }} €</span>
                                <span class="badge bg-primary">{{ $product->unit }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4>Aucun produit disponible</h4>
                    <p class="text-muted">Ce producteur n'a pas encore ajouté de produits</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
