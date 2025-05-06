@extends('layouts.app')

@section('title', 'Produits - AgriCarte')

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

    .filter-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        margin-top: -100px;
    }

    .product-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
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

    .producer-link {
        color: var(--primary-color);
        text-decoration: none;
    }

    .producer-link:hover {
        text-decoration: underline;
    }

    .price-tag {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .unit-badge {
        background-color: #f8f9fa;
        color: #6c757d;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .product-hero {
            min-height: 200px;
        }

        .filter-card {
            margin-top: -50px;
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
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Nos Produits</h1>
                    <p class="lead animate__animated animate__fadeInUp">Découvrez les produits frais et locaux de nos producteurs</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Filters -->
        <div class="filter-card animate__animated animate__fadeInUp">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nom du produit ou producteur...">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Catégorie</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="min_price" class="form-label">Prix min</label>
                    <input type="number" class="form-control" id="min_price" name="min_price" value="{{ request('min_price') }}" min="0" step="0.01">
                </div>
                <div class="col-md-2">
                    <label for="max_price" class="form-label">Prix max</label>
                    <input type="number" class="form-control" id="max_price" name="max_price" value="{{ request('max_price') }}" min="0" step="0.01">
                </div>
                <div class="col-md-1">
                    <label for="sort" class="form-label">Trier par</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récent</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 animate-on-scroll">
                    <div class="card product-card h-100">
                        <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                        <div class="category-badge">{{ $product->category->name }}</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-success">{{ number_format($product->price, 2) }} FCFA</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('favorites.toggle', $product) }}" class="btn btn-outline-danger favorite-toggle">
                                        <i class="{{ auth()->check() && auth()->user()->favorites && auth()->user()->favorites->contains($product) ? 'fas' : 'far' }} fa-heart"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
