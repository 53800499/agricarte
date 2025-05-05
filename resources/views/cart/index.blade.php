@extends('layouts.app')

@section('title', 'Mon panier')
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
                    <h1 class="display-4 fw-bold mb-4">Mon panier</h1>
                    <p class="lead">Les produits ajouter dans votre panier</p>
                </div>
            </div>
        </div>
    </section>
<div class="container py-5">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(count($cart) > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @foreach($cart as $id => $item)
                            <div class="row align-items-center mb-4 pb-4 border-bottom">
                                <div class="col-md-2">
                                    <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/default-product.jpg') }}"
                                         alt="{{ $item['name'] }}"
                                         class="img-fluid rounded">
                                </div>
                                <div class="col-md-4">
                                    <h5 class="mb-1">{{ $item['name'] }}</h5>
                                    <p class="text-muted mb-0">{{ number_format($item['price'], 2) }}€ / {{ $item['unit'] }}</p>
                                </div>
                                <div class="col-md-3">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="number"
                                               name="quantity"
                                               value="{{ $item['quantity'] }}"
                                               min="1"
                                               class="form-control form-control-sm"
                                               style="width: 70px;">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <p class="mb-0 fw-bold">{{ number_format($item['price'] * $item['quantity'], 2) }}€</p>
                                </div>
                                <div class="col-md-1 text-end">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Récapitulatif</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Sous-total</span>
                            <span>{{ number_format($total, 2) }}€</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Frais de livraison</span>
                            <span>Gratuit</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold">{{ number_format($total, 2) }}€</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mb-3">
                            <i class="fas fa-shopping-bag me-2"></i>Passer la commande
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Vider le panier
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
            <h3>Votre panier est vide</h3>
            <p class="text-muted">Découvrez nos produits et commencez vos achats.</p>
            <a href="{{ route('products') }}" class="btn btn-success">
                <i class="fas fa-store me-2"></i>Voir les produits
            </a>
        </div>
    @endif
</div>
@endsection
