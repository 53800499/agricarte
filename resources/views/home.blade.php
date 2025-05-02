@extends('layouts.app')

@section('title', 'Accueil - Produits frais des agriculteurs')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section h-full">
        <div class="container text-center hero-content">
            <h1 class="display-4 fw-bold mb-4">Trouvez et achetez des produits frais directement auprès des agriculteurs
                proches de chez vous !</h1>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ route('map') }}" class="btn btn-success btn-lg">Explorer la carte</a>
                <a href="#" class="btn btn-outline-light btn-lg">Publier un produit</a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Produits mis en avant</h2>
                <div class="d-flex gap-3">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Catégorie <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                            <li><a class="dropdown-item" href="{{ route('home', ['category' => 'all']) }}">Tous</a></li>
                            @foreach($categories as $category)
                                <li><a class="dropdown-item" href="{{ route('home', ['category' => $category->slug]) }}">
                                    {{ $category->name }}
                                </a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="priceDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Prix <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="priceDropdown">
                            <li><a class="dropdown-item" href="{{ route('home', ['sort' => 'price_asc']) }}">Croissant</a></li>
                            <li><a class="dropdown-item" href="{{ route('home', ['sort' => 'price_desc']) }}">Décroissant</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            id="distanceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Distance <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="distanceDropdown">
                            <li><a class="dropdown-item" href="{{ route('home', ['distance' => 5]) }}">&lt; 5 km</a></li>
                            <li><a class="dropdown-item" href="{{ route('home', ['distance' => 10]) }}">&lt; 10 km</a></li>
                            <li><a class="dropdown-item" href="{{ route('home', ['distance' => 20]) }}">&lt; 20 km</a></li>
                            <li><a class="dropdown-item" href="{{ route('home', ['distance' => 'all']) }}">Tous</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="position-relative">
                <button class="btn btn-light position-absolute top-50 start-0 translate-middle-y rounded-circle shadow-sm"
                    style="z-index: 1;" id="prevProduct">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <div class="row" id="productsContainer">
                    @forelse($featuredProducts as $product)
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card product-card h-100 border-0 shadow-sm">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/products/default.jpg') }}" 
                                    class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text fw-bold">{{ number_format($product->price, 2) }}€ / {{ $product->unit }}</p>
                                    <p class="text-muted small">{{ $product->user->address }}</p>
                                    <div class="mt-3">
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-success btn-sm">
                                            Voir le produit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">Aucun produit disponible pour le moment.</p>
                        </div>
                    @endforelse
                </div>

                <button class="btn btn-light position-absolute top-50 end-0 translate-middle-y rounded-circle shadow-sm"
                    style="z-index: 1;" id="nextProduct">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="text-center mt-3">
                <div class="d-inline-flex">
                    @for($i = 0; $i < ceil($featuredProducts->count() / 4); $i++)
                        <button class="btn btn-sm {{ $i === 0 ? 'btn-primary' : 'btn-outline-secondary' }} mx-1 rounded-circle pagination-dot"
                            style="width: 12px; height: 12px; padding: 0;" data-page="{{ $i }}"></button>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- Carte Interactive Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4">Carte interactive</h2>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div id="map-home" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Comment ça marche ?</h2>

            <div class="row text-center">
                <div class="col-md-4">
                    <div class="mb-4">
                        <i class="fas fa-map-marker-alt fa-3x text-success"></i>
                    </div>
                    <h4>Trouvez des agriculteurs</h4>
                    <p>Découvrez les agriculteurs près de chez vous grâce à notre carte interactive.</p>
                </div>
                <div class="col-md-4">
                    <div class="mb-4">
                        <i class="fas fa-shopping-basket fa-3x text-success"></i>
                    </div>
                    <h4>Choisissez vos produits</h4>
                    <p>Parcourez les produits frais et de saison proposés par les agriculteurs locaux.</p>
                </div>
                <div class="col-md-4">
                    <div class="mb-4">
                        <i class="fas fa-handshake fa-3x text-success"></i>
                    </div>
                    <h4>Achetez en direct</h4>
                    <p>Contactez l'agriculteur et achetez directement à la ferme ou sur les marchés.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-white py-5">
        <!-- Testimonials Section -->
        <div class="container mb-5">
            <h2 class="text-center mb-4">Témoignages</h2>
            <div class="text-center my-4">
                Découvrez ce que nos clients pensent de nos services
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 h-100 shadow">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/testimonials/testimonial1.jpg') }}" alt="Témoignage"
                                class="img-fluid rounded mb-4" style="max-height: 230px; object-fit: cover;">
                            <div class="mb-4">
                                <i class="fas fa-quote-left text-success fa-2x me-2 opacity-50"></i>
                                <span class="text-muted fst-italic">Ce service est excellent! Grâce à leur professionnalisme, j'ai pu
                                    faire évoluer mon entreprise rapidement.</span>
                                <i class="fas fa-quote-right text-success fa-2x ms-2 opacity-50"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Saul Goodman</h5>
                                <p class="text-muted">PDG & Fondateur</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 h-100  shadow">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/testimonials/testimonial2.jpg') }}" alt="Témoignage"
                                class="img-fluid rounded mb-4" style="max-height: 230px; object-fit: cover;">
                            <div class="mb-4">
                                <i class="fas fa-quote-left text-success fa-2x me-2 opacity-50"></i>
                                <span class="text-muted fst-italic">Un accompagnement de qualité! Une équipe à l'écoute et des
                                    résultats à la hauteur de mes attentes.</span>
                                <i class="fas fa-quote-right text-success fa-2x ms-2 opacity-50"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Sara Wilson</h5>
                                <p class="text-muted">Designer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 h-100  shadow">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/testimonials/testimonial3.jpg') }}" alt="Témoignage"
                                class="img-fluid rounded mb-4" style="max-height: 230px; object-fit: cover;">
                            <div class="mb-4">
                                <i class="fas fa-quote-left text-success fa-2x me-2 opacity-50"></i>
                                <span class="text-muted fst-italic">Une expérience incroyable! J'ai pu améliorer mes ventes et
                                    fidéliser mes clients grâce à leur aide.</span>
                                <i class="fas fa-quote-right text-success fa-2x ms-2 opacity-50"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Jena Karlis</h5>
                                <p class="text-muted">Propriétaire de boutique</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" defer></script>
    <script>
        function initMap() {
            // Coordonnées centrales de la France
            const center = {
                lat: 46.603354,
                lng: 1.888334
            };

            // Créer la carte
            const map = new google.maps.Map(document.getElementById("map-home"), {
                zoom: 6,
                center: center,
            });

            // Exemple de marqueurs pour les producteurs
            const producers = [{
                    position: {
                        lat: 47.218371,
                        lng: -1.553621
                    },
                    title: "Producteur à Nantes"
                },
                {
                    position: {
                        lat: 44.837789,
                        lng: -0.579180
                    },
                    title: "Producteur à Bordeaux"
                },
                {
                    position: {
                        lat: 45.764043,
                        lng: 4.835659
                    },
                    title: "Producteur à Lyon"
                },
                {
                    position: {
                        lat: 43.604652,
                        lng: 1.444209
                    },
                    title: "Producteur à Toulouse"
                }
            ];

            // Ajouter les marqueurs
            producers.forEach(({
                position,
                title
            }) => {
                const marker = new google.maps.Marker({
                    position,
                    map,
                    title,
                });
            });
        }
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsContainer = document.getElementById('productsContainer');
        const prevButton = document.getElementById('prevProduct');
        const nextButton = document.getElementById('nextProduct');
        const paginationDots = document.querySelectorAll('.pagination-dot');
        let currentPage = 0;
        const productsPerPage = 4;

        function showProducts(page) {
            const products = productsContainer.querySelectorAll('.col-md-3');
            products.forEach((product, index) => {
                if (index >= page * productsPerPage && index < (page + 1) * productsPerPage) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });

            // Update pagination dots
            paginationDots.forEach((dot, index) => {
                if (index === page) {
                    dot.classList.remove('btn-outline-secondary');
                    dot.classList.add('btn-primary');
                } else {
                    dot.classList.remove('btn-primary');
                    dot.classList.add('btn-outline-secondary');
                }
            });

            // Update button states
            prevButton.disabled = page === 0;
            nextButton.disabled = page >= Math.ceil(products.length / productsPerPage) - 1;
        }

        prevButton.addEventListener('click', () => {
            if (currentPage > 0) {
                currentPage--;
                showProducts(currentPage);
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentPage < Math.ceil(productsContainer.querySelectorAll('.col-md-3').length / productsPerPage) - 1) {
                currentPage++;
                showProducts(currentPage);
            }
        });

        paginationDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentPage = index;
                showProducts(currentPage);
            });
        });

        // Initialize
        showProducts(0);
    });
    </script>
@endsection
