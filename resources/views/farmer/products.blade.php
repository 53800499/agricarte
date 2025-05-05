@extends('layouts.app')

@section('title', 'Mes Produits - AgriCarte')

@push('styles')
<style>
    .product-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slide1.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-bottom: 2rem;
    }

    .product-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
    }

    .category-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: var(--primary-color);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    .stock-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    .stock-badge.in-stock {
        background-color: var(--success-color);
        color: white;
    }

    .stock-badge.out-stock {
        background-color: var(--danger-color);
        color: white;
    }

    .product-actions {
        display: flex;
        gap: 0.5rem;
    }

    .product-actions .btn {
        flex: 1;
    }

    @media (max-width: 768px) {
        .product-hero {
            min-height: 200px;
        }

        .product-image {
            height: 180px;
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
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Mes Produits</h1>
                <p class="lead animate__animated animate__fadeInUp">Gérez vos produits et vos stocks</p>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Liste de vos produits</h2>
        <a href="{{ route('farmer.products.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Ajouter un produit
        </a>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-4">
                <div class="card product-card">
                    <div class="position-relative">
                        <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('images/products/logo.jpg') }}" 
                             class="card-img-top product-image"
                             alt="{{ $product->name }}">
                        <span class="category-badge">{{ $product->category->name }}</span>
                        <span class="stock-badge {{ $product->stock > 0 ? 'in-stock' : 'out-stock' }}">
                            {{ $product->stock > 0 ? 'En stock' : 'Rupture' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 mb-0 text-success">{{ number_format($product->price, 2) }} €</span>
                            <span class="text-muted">{{ $product->stock }} en stock</span>
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('farmer.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty-products.svg') }}" alt="Aucun produit" class="mb-4" style="max-width: 200px;">
                    <h4 class="text-muted">Vous n'avez pas encore de produits</h4>
                    <p class="text-muted">Commencez par ajouter votre premier produit</p>
                    <a href="{{ route('farmer.products.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Ajouter un produit
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection 