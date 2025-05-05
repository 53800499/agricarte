@extends('layouts.app')

@section('title', 'Produits - AgriCarte')

@push('styles')
<style>
    .products-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('images/slide1.jpg') }}');
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
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filter-title {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .filter-title i {
        margin-right: 0.5rem;
    }

    .form-check {
        margin-bottom: 0.5rem;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .product-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
        border-radius: 15px 15px 0 0;
    }

    .product-body {
        padding: 1.5rem;
    }

    .product-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .product-description {
        color: #6c757d;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .product-meta i {
        color: var(--primary-color);
        margin-right: 0.5rem;
    }

    .pagination {
        margin-top: 2rem;
    }

    .page-link {
        color: var(--primary-color);
        border: none;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 5px;
    }

    .page-link:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        color: white;
    }

    @media (max-width: 768px) {
        .products-hero {
            min-height: 200px;
        }

        .product-image {
            height: 150px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="products-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Nos Produits</h1>
                    <p class="lead animate__animated animate__fadeInUp">Découvrez notre sélection de produits locaux et de qualité</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <!-- Filters -->
            <div class="col-lg-3">
                <div class="filter-card animate__animated animate__fadeInLeft">
                    <h5 class="filter-title">
                        <i class="fas fa-filter"></i>Filtres
                    </h5>
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Catégories</label>
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]"
                                        value="{{ $category->id }}" id="category{{ $category->id }}"
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prix</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price"
                                        placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price"
                                        placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tri</label>
                            <select class="form-select" name="sort">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Appliquer les filtres
                        </button>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="row g-4">
                    @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mt-4">
                    <div class="card product-card h-100">
                        <img src="{{ asset($product['image']) }}" class="card-img-top" alt="{{ $product['name'] }}">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">{{ $product['category'] }}</span>
                            <h5 class="card-title">{{ $product['name'] }}</h5>
                            <p class="card-text">
                                <strong>{{ $product['price'] }} €/{{ $product['unit'] }}</strong><br>
                                <small class="text-muted">{{ $product['producer'] }}</small>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-success">Voir détails</a>
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
