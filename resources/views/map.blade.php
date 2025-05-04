@extends('layouts.app')

@section('title', 'Carte des Producteurs - AgriCarte')

@push('styles')
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

    .producer-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }

    .producer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .producer-image {
        height: 200px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }

    .category-badge {
        background: var(--primary-color);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
    }

    .search-box {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .filter-buttons {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .map-hero {
            min-height: 200px;
        }

        #map {
            height: 400px;
        }

        .search-box, .filter-buttons {
            position: relative;
            top: 0;
            left: 0;
            right: 0;
            margin-bottom: 1rem;
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
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Carte des Producteurs</h1>
                    <p class="lead animate__animated animate__fadeInUp">Trouvez les producteurs près de chez vous</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <!-- Map Section -->
            <div class="col-lg-8 mb-4">
                <div class="position-relative">
                    <div id="map"></div>
                    <div class="search-box">
                        <div class="input-group">
                            <input type="text" id="search-input" class="form-control" placeholder="Rechercher un lieu...">
                            <button class="btn btn-primary" type="button" id="search-button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="filter-buttons">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary active" data-category="all">Tous</button>
                            @foreach($categories as $category)
                                <button type="button" class="btn btn-outline-primary" data-category="{{ $category->slug }}">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Producers List -->
            <div class="col-lg-4">
                <div class="producers-list">
                    @foreach($producers as $producer)
                        <div class="producer-card" data-categories="{{ $producer->products->pluck('category.slug')->implode(',') }}">
                            <img src="{{ $producer->image ? asset('storage/' . $producer->image) : asset('images/default-producer.jpg') }}"
                                class="producer-image w-100" alt="{{ $producer->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $producer->name }}</h5>
                                <p class="card-text text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $producer->address }}
                                </p>
                                <p class="card-text">{{ Str::limit($producer->description, 100) }}</p>
                                <div class="mb-3">
                                    @foreach($producer->products->pluck('category')->unique() as $category)
                                        <span class="category-badge">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                                <a href="{{ route('producers.show', $producer) }}" class="btn btn-primary w-100">
                                    Voir les produits
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let map;
    let markers = [];
    let infoWindow;

    function initMap() {
        // Default coordinates for Benin
        const beninCenter = { lat: 9.3077, lng: 2.3158 };

        map = new google.maps.Map(document.getElementById('map'), {
            center: beninCenter,
            zoom: 7,
            styles: [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                }
            ]
        });

        infoWindow = new google.maps.InfoWindow();

        // Add markers for each producer
        @foreach($producers as $producer)
            const marker = new google.maps.Marker({
                position: { lat: {{ $producer->latitude }}, lng: {{ $producer->longitude }} },
                map: map,
                title: '{{ $producer->name }}',
                icon: {
                    url: '{{ asset('images/marker.png') }}',
                    scaledSize: new google.maps.Size(40, 40)
                }
            });

            marker.addListener('click', () => {
                infoWindow.setContent(`
                    <div class="p-2">
                        <h6>{{ $producer->name }}</h6>
                        <p class="mb-1">{{ $producer->address }}</p>
                        <a href="{{ route('producers.show', $producer) }}" class="btn btn-sm btn-primary">
                            Voir les produits
                        </a>
                    </div>
                `);
                infoWindow.open(map, marker);
            });

            markers.push(marker);
        @endforeach

        // Search functionality
        const searchBox = new google.maps.places.SearchBox(document.getElementById('search-input'));

        map.addListener('bounds_changed', () => {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener('places_changed', () => {
            const places = searchBox.getPlaces();
            if (places.length === 0) return;

            const bounds = new google.maps.LatLngBounds();
            places.forEach(place => {
                if (!place.geometry) return;
                bounds.extend(place.geometry.location);
            });
            map.fitBounds(bounds);
        });

        // Filter functionality
        document.querySelectorAll('.filter-buttons .btn').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.filter-buttons .btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const category = button.dataset.category;
                const producers = document.querySelectorAll('.producer-card');

                producers.forEach(producer => {
                    if (category === 'all' || producer.dataset.categories.includes(category)) {
                        producer.style.display = 'block';
                    } else {
                        producer.style.display = 'none';
                    }
                });
            });
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initMap" async defer></script>
@endpush
