@extends('layouts.app')

@section('title', 'Produits - AgriCarte')

@section('content')
<!-- Hero Section -->
    <section class="hero-section bg-light">
        <div class="container text-center hero-content">
            <h1 class="display-4 fw-bold mb-4">Nos produits</h1>
        </div>
    </section>
    <div class="container py-5">
        <p class="text-center mb-4">
            Decouvrez tous nos produits 
        </p>
        <!-- Filters Section -->
        <div class="row filter-section">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Rechercher un produit...">
                    <button class="btn btn-success" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-end gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Catégorie <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                            @foreach($categories as $category)
                                <li><a class="dropdown-item" href="#">{{ $category }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="priceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Prix <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="priceDropdown">
                            <li><a class="dropdown-item" href="#">Croissant</a></li>
                            <li><a class="dropdown-item" href="#">Décroissant</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="distanceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Distance <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="distanceDropdown">
                            <li><a class="dropdown-item" href="#">< 5 km</a></li>
                            <li><a class="dropdown-item" href="#">< 10 km</a></li>
                            <li><a class="dropdown-item" href="#">< 20 km</a></li>
                            <li><a class="dropdown-item" href="#">Tous</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="row gap-0">
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
                                <a href="#" class="btn btn-sm btn-outline-success">Voir détails</a>
                                <button class="btn btn-sm btn-success">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <nav class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Suivant</a>
                </li>
            </ul>
        </nav>
    </div>
@endsection
