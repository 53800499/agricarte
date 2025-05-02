@extends('layouts.app')

@section('title', 'Nos Produits - Agricarte')

@section('content')
<div class="container py-5">
    <!-- En-tête avec recherche et filtres -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h2 mb-0">Nos Produits</h1>
            <p class="text-muted">Découvrez nos produits frais et locaux</p>
        </div>
        <div class="col-md-6">
            <div class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Rechercher un produit...">
                </div>
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="collapse mb-4" id="filtersCollapse">
        <div class="card card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Catégorie</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prix</label>
                    <select class="form-select" id="priceFilter">
                        <option value="">Tous les prix</option>
                        <option value="asc">Prix croissant</option>
                        <option value="desc">Prix décroissant</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Distance</label>
                    <select class="form-select" id="distanceFilter">
                        <option value="">Toutes les distances</option>
                        <option value="5">Moins de 5 km</option>
                        <option value="10">Moins de 10 km</option>
                        <option value="20">Moins de 20 km</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Options</label>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="organicFilter">
                            <label class="form-check-label" for="organicFilter">Bio</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="featuredFilter">
                            <label class="form-check-label" for="featuredFilter">En vedette</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des produits -->
    <div class="row" id="productsGrid">
        @forelse($products as $product)
            <div class="col-md-4 col-lg-3 mb-4 product-card" 
                 data-category="{{ $product->category_id ?? '' }}"
                 data-price="{{ $product->price ?? 0 }}"
                 data-organic="{{ $product->is_organic ? '1' : '0' }}"
                 data-featured="{{ $product->is_featured ? '1' : '0' }}">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/products/default.jpg') }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 p-2">
                            @if($product->is_organic)
                                <span class="badge bg-success">Bio</span>
                            @endif
                            @if($product->is_featured)
                                <span class="badge bg-warning">En vedette</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted small mb-2">{{ $product->category->name ?? 'Non catégorisé' }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 text-primary mb-0">{{ number_format($product->price ?? 0, 2) }}€</span>
                            <span class="badge bg-{{ ($product->stock_quantity ?? 0) > 0 ? 'success' : 'danger' }}">
                                {{ ($product->stock_quantity ?? 0) > 0 ? 'En stock' : 'Rupture' }}
                            </span>
                        </div>
                        <p class="card-text small text-muted mb-3">
                            {{ Str::limit($product->description ?? '', 100) }}
                        </p>
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ isset($product->user->profile_photo) ? asset('storage/' . $product->user->profile_photo) : asset('images/farmers/default.jpg') }}" 
                                 class="rounded-circle me-2" 
                                 style="width: 30px; height: 30px; object-fit: cover;"
                                 alt="{{ $product->user->name ?? 'Agriculteur' }}">
                            <small class="text-muted">{{ $product->user->name ?? 'Agriculteur' }}</small>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-success">
                                Voir le produit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty-products.svg') }}" alt="Aucun produit" class="mb-4" style="max-width: 200px;">
                    <h4 class="text-muted">Aucun produit trouvé</h4>
                    <p class="text-muted">Essayez de modifier vos critères de recherche</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const priceFilter = document.getElementById('priceFilter');
    const distanceFilter = document.getElementById('distanceFilter');
    const organicFilter = document.getElementById('organicFilter');
    const featuredFilter = document.getElementById('featuredFilter');
    const productsGrid = document.getElementById('productsGrid');

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const selectedPrice = priceFilter.value;
        const selectedDistance = distanceFilter.value;
        const showOrganic = organicFilter.checked;
        const showFeatured = featuredFilter.checked;

        const productCards = productsGrid.querySelectorAll('.product-card');
        
        productCards.forEach(card => {
            const productName = card.querySelector('.card-title').textContent.toLowerCase();
            const productCategory = card.getAttribute('data-category');
            const productPrice = parseFloat(card.getAttribute('data-price'));
            const isOrganic = card.getAttribute('data-organic') === '1';
            const isFeatured = card.getAttribute('data-featured') === '1';

            let matchesSearch = productName.includes(searchTerm);
            let matchesCategory = !selectedCategory || productCategory === selectedCategory;
            let matchesOrganic = !showOrganic || isOrganic;
            let matchesFeatured = !showFeatured || isFeatured;

            // Logique de filtrage par prix
            let matchesPrice = true;
            if (selectedPrice === 'asc') {
                // Trier par prix croissant
                const sortedCards = Array.from(productCards).sort((a, b) => {
                    return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                });
                productsGrid.innerHTML = '';
                sortedCards.forEach(sortedCard => productsGrid.appendChild(sortedCard));
            } else if (selectedPrice === 'desc') {
                // Trier par prix décroissant
                const sortedCards = Array.from(productCards).sort((a, b) => {
                    return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                });
                productsGrid.innerHTML = '';
                sortedCards.forEach(sortedCard => productsGrid.appendChild(sortedCard));
            }

            // Afficher/masquer la carte en fonction des critères
            if (matchesSearch && matchesCategory && matchesOrganic && matchesFeatured) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Écouteurs d'événements pour les filtres
    searchInput.addEventListener('input', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    priceFilter.addEventListener('change', filterProducts);
    distanceFilter.addEventListener('change', filterProducts);
    organicFilter.addEventListener('change', filterProducts);
    featuredFilter.addEventListener('change', filterProducts);
});
</script>
@endsection
