@extends('layouts.app')

@section('title', 'Accueil - AgriCarte')

@push('styles')
<style>
    .hero-section {
        min-height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/slide1.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -76px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/images/slide1.jpg');
        opacity: 0.1;
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        padding: 2rem;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        animation: fadeInDown 1s ease-out;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 1s ease-out 0.3s backwards;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        animation: fadeInUp 1s ease-out 0.6s backwards;
    }

    .hero-btn {
        padding: 1rem 2rem;
        font-size: 1.1rem;
        border-radius: 50px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .hero-btn-primary {
        background: var(--primary-color);
        border: none;
    }

    .hero-btn-outline {
        border: 2px solid white;
        background: transparent;
    }

    .hero-btn-outline:hover {
        background: white;
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .hero-buttons {
            flex-direction: column;
        }

        .hero-btn {
            width: 100%;
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(40, 167, 69, 0.1);
        border-radius: 50%;
        margin: 0 auto 20px;
    }

    .product-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
    }

    .category-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(40, 167, 69, 0.9);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .testimonial-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .testimonial-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto;
    }

    .newsletter-section {
        background: linear-gradient(rgba(40, 167, 69, 0.9), rgba(40, 167, 69, 0.9)), url('/images/newsletter-bg.jpg');
        background-size: cover;
        background-position: center;
        color: white;
    }

    .newsletter-form .form-control {
        border: none;
        border-radius: 30px;
        padding: 15px 25px;
    }

    .newsletter-form .btn {
        border-radius: 30px;
        padding: 15px 30px;
    }

    .map-section {
        position: relative;
        height: 600px;
    }

    #map {
        height: 100%;
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .map-overlay {
        position: absolute;
        top: 20px;
        left: 20px;
        background: white;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        width: 300px;
    }

    .map-search {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 1rem;
    }

    .map-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .map-filter-btn {
        padding: 0.25rem 0.75rem;
        border: 1px solid var(--primary-color);
        border-radius: 20px;
        background: white;
        color: var(--primary-color);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .map-filter-btn:hover,
    .map-filter-btn.active {
        background: var(--primary-color);
        color: white;
    }

    .info-window {
        padding: 1rem;
    }

    .info-window img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 1rem;
    }

    .info-window h4 {
        margin: 0;
        font-size: 1.1rem;
    }

    .info-window p {
        margin: 0.5rem 0;
        color: #666;
    }

    .info-window .products {
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }

    .info-window .products span {
        display: inline-block;
        background: #f0f0f0;
        padding: 0.25rem 0.5rem;
        border-radius: 15px;
        margin: 0.25rem;
    }

    @media (max-width: 768px) {
        .map-section {
            height: 400px;
        }

        .map-overlay {
            width: calc(100% - 40px);
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Bienvenue sur AgriCarte</h1>
                    <p class="lead animate__animated animate__fadeInUp">Découvrez les producteurs locaux et leurs produits frais</p>
                    <div class="mt-4 animate__animated animate__fadeInUp">
                        <a href="{{ route('products') }}" class="btn btn-success btn-lg me-3">Voir les produits</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Devenir producteur</a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Features Section -->
    <section class="features-section py-5">
        <div class="container">
            <div class="row align-content-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-4">
                            <i class="fas fa-map-marker-alt fa-2x text-success"></i>
                        </div>
                        <h3>Localisation</h3>
                        <p>Trouvez les producteurs près de chez vous</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-4">
                            <i class="fas fa-leaf fa-2x text-success"></i>
                        </div>
                        <h3>Produits Frais</h3>
                        <p>Des produits locaux et de saison</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-4">
                            <i class="fas fa-handshake fa-2x text-success"></i>
                        </div>
                        <h3>Circuit Court</h3>
                        <p>Directement du producteur au consommateur</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="map-overlay animate__animated animate__fadeInLeft">
            <input type="text" class="map-search" placeholder="Rechercher un producteur...">
            <div class="map-filters">
                <button class="map-filter-btn active" data-category="">Tous</button>
                @foreach($categories as $category)
                    <button class="map-filter-btn" data-category="{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            </div>
        </div>
        <div id="map"></div>
    </section>
    <!-- Featured Products Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Produits à la une</h2>
                <p class="lead text-muted">Découvrez nos meilleurs produits du moment</p>
            </div>
            <div class="row g-4">
                @foreach($featuredProducts as $product)
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
            <div class="text-center mt-5">
                <a href="{{ route('products') }}" class="btn btn-success btn-lg">
                    Voir tous les produits <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Comment ça marche ?</h2>
                <p class="lead text-muted">Découvrez nos produits en quelques étapes simples</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 animate-on-scroll">
                    <div class="text-center">
                        <div class="feature-icon mb-4">
                            <i class="fas fa-search fa-2x text-success"></i>
                        </div>
                        <h3 class="h4 mb-3">1. Trouvez un producteur</h3>
                        <p class="text-muted">Utilisez notre carte interactive pour trouver les producteurs près de chez vous.</p>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll">
                    <div class="text-center">
                        <div class="feature-icon mb-4">
                            <i class="fas fa-shopping-basket fa-2x text-success"></i>
                        </div>
                        <h3 class="h4 mb-3">2. Choisissez vos produits</h3>
                        <p class="text-muted">Parcourez le catalogue de produits et ajoutez-les à votre panier.</p>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll">
                    <div class="text-center">
                        <div class="feature-icon mb-4">
                            <i class="fas fa-truck fa-2x text-success"></i>
                        </div>
                        <h3 class="h4 mb-3">3. Recevez vos produits</h3>
                        <p class="text-muted">Récupérez vos produits directement chez le producteur ou optez pour la livraison.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Ce qu'en disent nos clients</h2>
                <p class="lead text-muted">Découvrez les témoignages de nos utilisateurs satisfaits</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 animate-on-scroll">
                    <div class="card testimonial-card h-100">
                        <img src="{{ asset('images/testimonials/testimonial1.jpg') }}" class="testimonial-image" alt="Client 1">
                        <div class="card-body">
                            <p class="card-text">"Grâce à AgriCarte, je peux facilement trouver des produits frais et locaux. Une vraie révolution dans ma façon de consommer !"</p>
                            <h5 class="card-title mb-0">Marie D.</h5>
                            <small class="text-muted">Client depuis 2022</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll">
                    <div class="card testimonial-card h-100">
                        <img src="{{ asset('images/testimonials/testimonial2.jpg') }}" class="testimonial-image" alt="Client 2">
                        <div class="card-body">
                            <p class="card-text">"En tant que producteur, AgriCarte m'a permis de développer ma clientèle locale. Une plateforme simple et efficace !"</p>
                            <h5 class="card-title mb-0">Jean P.</h5>
                            <small class="text-muted">Producteur depuis 2021</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll">
                    <div class="card testimonial-card h-100">
                        <img src="{{ asset('images/testimonials/testimonial3.jpg') }}" class="testimonial-image" alt="Client 3">
                        <div class="card-body">
                            <p class="card-text">"La qualité des produits est exceptionnelle. Je recommande vivement AgriCarte à tous les amateurs de produits frais !"</p>
                            <h5 class="card-title mb-0">Sophie L.</h5>
                            <small class="text-muted">Client depuis 2023</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-5 newsletter-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold mb-4">Restez informés</h2>
                    <p class="lead mb-5">Inscrivez-vous à notre newsletter pour recevoir les dernières actualités et offres spéciales.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form" data-ajax="true">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Votre adresse email" required>
                            <button class="btn btn-light" type="submit">S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    let map;
    let markers = [];
    let infoWindow;

    function initMap() {
        // Coordonnées par défaut centrées sur le Bénin
        const defaultLocation = { lat: 9.3077, lng: 2.3158 };

        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 7,
            styles: [
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [{"color": "#444444"}]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [{"color": "#f2f2f2"}]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [{"visibility": "off"}]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [{"saturation": -100}, {"lightness": 45}]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "all",
                    "stylers": [{"visibility": "simplified"}]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.icon",
                    "stylers": [{"visibility": "off"}]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [{"visibility": "off"}]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [{"color": "#46bcec"}, {"visibility": "on"}]
                }
            ]
        });

        infoWindow = new google.maps.InfoWindow();

        // Charger les producteurs
        loadProducers();

        // Écouter les changements de filtre
        document.querySelectorAll('.map-filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.map-filter-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                loadProducers(this.dataset.category);
            });
        });

        // Écouter la recherche
        const searchInput = document.querySelector('.map-search');
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadProducers(null, this.value);
            }, 500);
        });
    }

    function loadProducers(category = null, search = null) {
        // Supprimer les marqueurs existants
        markers.forEach(marker => marker.setMap(null));
        markers = [];

        // Charger les producteurs
        fetch(`/api/producers?${new URLSearchParams({
            category: category || '',
            search: search || ''
        })}`)
        .then(response => response.json())
        .then(producers => {
            producers.forEach(producer => {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(producer.latitude), lng: parseFloat(producer.longitude) },
                    map: map,
                    title: producer.name
                });

                const content = `
                    <div class="info-window">
                        <div class="d-flex align-items-center">
                            <img src="${producer.profile_image}" alt="${producer.name}">
                            <h4>${producer.name}</h4>
                        </div>
                        <p><i class="fas fa-map-marker-alt"></i> ${producer.address}</p>
                        <div class="products">
                            ${producer.products.map(product => `
                                <span>${product.name} (${product.price}FCFA/${product.unit})</span>
                            `).join('')}
                        </div>
                    </div>
                `;

                marker.addListener('click', () => {
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
            });
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>
@endpush
