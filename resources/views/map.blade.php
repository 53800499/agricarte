@extends('layouts.app')

@section('title', 'Carte des Producteurs - AgriCarte')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .map-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slide1.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -76px;
    }

    #map {
        height: 600px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .map-controls {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 300px;
    }

    .search-box {
        margin-bottom: 15px;
    }

    .filter-section {
        margin-bottom: 15px;
    }

    .filter-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--primary-color);
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .filter-btn {
        padding: 5px 10px;
        border: 1px solid var(--primary-color);
        border-radius: 20px;
        background: white;
        color: var(--primary-color);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--primary-color);
        color: white;
    }

    .producer-list {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 300px;
        max-height: 500px;
        overflow-y: auto;
    }

    .producer-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .producer-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .producer-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary-color);
    }

    .producer-info {
        padding: 10px;
    }

    .producer-name {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .producer-address {
        font-size: 0.9rem;
        color: #666;
    }

    .producer-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 5px;
    }

    .category-badge {
        background: var(--primary-color);
        color: white;
        padding: 2px 8px;
        border-radius: 15px;
        font-size: 0.8rem;
    }

    .info-window {
        padding: 10px;
        min-width: 200px;
    }

    .info-window img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }

    .info-window h4 {
        margin: 0;
        font-size: 1.1rem;
    }

    .info-window p {
        margin: 5px 0;
        color: #666;
    }

    .info-window .products {
        margin-top: 5px;
        font-size: 0.9rem;
    }

    .info-window .products span {
        display: inline-block;
        background: #f0f0f0;
        padding: 2px 8px;
        border-radius: 15px;
        margin: 2px;
    }

    .info-window .btn-details {
        display: block;
        width: 100%;
        margin-top: 10px;
        padding: 5px;
        text-align: center;
        background: var(--primary-color);
        color: white;
        border-radius: 5px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .info-window .btn-details:hover {
        background: var(--primary-dark);
    }

    @media (max-width: 768px) {
        .map-controls,
        .producer-list {
            position: relative;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            margin-bottom: 15px;
        }

        #map {
            height: 400px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="map-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Carte des Producteurs</h1>
                    <p class="lead">Trouvez les producteurs près de chez vous</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row bordage">
            <div class="col-12">
                <div class="position-relative">
                    <!-- Contrôles de la carte -->
                    <div class="map-controls">
                        <div class="search-box">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Rechercher un lieu...">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-title">Filtrer par catégorie</div>
                            <div class="filter-buttons">
                                <button type="button" class="filter-btn active" data-category="all">Tous</button>
                                @foreach($categories as $category)
                                    <button type="button" class="filter-btn" data-category="{{ $category->slug }}">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-title">Filtrer par produit</div>
                            <div class="input-group">
                                <input type="text" id="product-search" class="form-control" placeholder="Nom du produit...">
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-title">Filtrer par distance</div>
                            <div class="input-group">
                                <input type="range" class="form-range" id="distance-range" min="1" max="50" value="10">
                                <span class="input-group-text" id="distance-value">10 km</span>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des producteurs -->
                    <div class="producer-list">
                        @foreach($producers as $producer)
                            <div class="producer-card"
                                 data-id="{{ $producer->id }}"
                                 data-lat="{{ $producer->latitude }}"
                                 data-lng="{{ $producer->longitude }}"
                                 data-categories="{{ $producer->products->pluck('category.slug')->implode(',') }}"
                                 data-products="{{ $producer->products->pluck('name')->implode(',') }}">
                                <div class="d-flex align-items-center p-2">
                                    <img src="{{ $producer->profile_image ? asset('storage/' . $producer->profile_image) : asset('images/default-farmer.jpg') }}"
                                         alt="{{ $producer->name }}"
                                         class="producer-image me-3">
                                    <div class="producer-info">
                                        <div class="producer-name">{{ $producer->name }}</div>
                                        <div class="producer-address">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $producer->address }}
                                        </div>
                                        <div class="producer-categories">
                                            @foreach($producer->products->pluck('category')->unique() as $category)
                                                <span class="category-badge">{{ $category->name }}</span>
                                            @endforeach
                                        </div>
                                        <div class="producer-products mt-2">
                                            <small class="text-muted">Produits: {{ $producer->products->pluck('name')->implode(', ') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Carte -->
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation de la carte
        const map = L.map('map').setView([9.3077, 2.3158], 7); // Centre sur le Bénin

        // Ajout de la couche OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Marqueurs des producteurs
        const markers = [];
        const producerCards = document.querySelectorAll('.producer-card');

        // Création des marqueurs
        producerCards.forEach(card => {
            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            const name = card.querySelector('.producer-name').textContent;
            const address = card.querySelector('.producer-address').textContent;
            const image = card.querySelector('.producer-image').src;
            const categories = Array.from(card.querySelectorAll('.category-badge')).map(badge => badge.textContent);
            const products = card.dataset.products.split(',');
            const producerId = card.dataset.id;

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'producer-marker',
                    html: '<i class="fas fa-map-marker-alt fa-2x text-primary"></i>'
                })
            }).addTo(map);

            marker.bindPopup(`
                <div class="info-window">
                    <div class="d-flex align-items-center">
                        <img src="${image}" alt="${name}">
                        <h4>${name}</h4>
                    </div>
                    <p><i class="fas fa-map-marker-alt"></i> ${address}</p>
                    <div class="products">
                        <strong>Produits:</strong><br>
                        ${products.map(product => `<span>${product}</span>`).join('')}
                    </div>
                    <a href="/producers/${producerId}" class="btn-details">
                        Voir les détails
                    </a>
                </div>
            `);

            markers.push({
                marker,
                card,
                categories: card.dataset.categories.split(','),
                products: products
            });

            // Interaction entre la carte et la liste
            card.addEventListener('click', () => {
                map.setView([lat, lng], 13);
                marker.openPopup();
            });

            marker.addEventListener('click', () => {
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                card.classList.add('active');
                setTimeout(() => card.classList.remove('active'), 1000);
            });
        });

        // Filtrage par catégorie
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const category = button.dataset.category;
                markers.forEach(({ marker, card, categories }) => {
                    const shouldShow = category === 'all' || categories.includes(category);
                    marker.setOpacity(shouldShow ? 1 : 0);
                    card.style.display = shouldShow ? 'block' : 'none';
                });
            });
        });

        // Filtrage par produit
        const productSearch = document.getElementById('product-search');
        productSearch.addEventListener('input', () => {
            const searchTerm = productSearch.value.toLowerCase();

            markers.forEach(({ marker, card, products }) => {
                const shouldShow = searchTerm === '' ||
                    products.some(product => product.toLowerCase().includes(searchTerm));
                marker.setOpacity(shouldShow ? 1 : 0);
                card.style.display = shouldShow ? 'block' : 'none';
            });
        });

        // Filtrage par distance
        const distanceRange = document.getElementById('distance-range');
        const distanceValue = document.getElementById('distance-value');

        distanceRange.addEventListener('input', () => {
            const distance = parseInt(distanceRange.value);
            distanceValue.textContent = `${distance} km`;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    markers.forEach(({ marker, card }) => {
                        const lat = parseFloat(card.dataset.lat);
                        const lng = parseFloat(card.dataset.lng);
                        const distanceToUser = calculateDistance(userLat, userLng, lat, lng);

                        const shouldShow = distanceToUser <= distance;
                        marker.setOpacity(shouldShow ? 1 : 0);
                        card.style.display = shouldShow ? 'block' : 'none';
                    });
                });
            }
        });

        // Calcul de la distance entre deux points
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Rayon de la Terre en km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        // Recherche de lieu
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');

        searchButton.addEventListener('click', () => {
            const query = searchInput.value;
            if (query) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const { lat, lon } = data[0];
                            map.setView([lat, lon], 13);
                        }
                    });
            }
        });
    });
</script>
@endpush
