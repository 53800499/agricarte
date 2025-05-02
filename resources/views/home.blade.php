@extends('layouts.app')

@section('title', 'Accueil - Produits frais des agriculteurs')

@section('content')

    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden">
        <div id="carouselExampleCaptions" class="carousel slide hero-carousel" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/slide2.jpg') }}" class="d-block w-100 object-fit-cover"
                        style="height: 500px;" alt="...">
                    <div
                        class="carousel-caption d-flex flex-column justify-content-center align-items-center top-0 bottom-0 start-0 end-0 text-center">
                        <h1 class="fw-bold">Trouvez et achetez des produits frais...</h1>
                        <div class="mt-3">
                            <a href="{{ route('map') }}" class="btn btn-success btn-lg">Explorer la carte</a>
                            <a href="#" class="btn btn-outline-light btn-lg">Publier un produit</a>
                        </div>
                    </div>

                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide1.jpg') }}" class="d-block w-100 object-fit-cover"
                        style="height: 500px;" alt="...">
                    <div
                        class="carousel-caption d-flex flex-column justify-content-center align-items-center top-0 bottom-0 start-0 end-0 text-center">
                        <h1 class="fw-bold">Trouvez et achetez des produits frais...</h1>
                        <div class="mt-3">
                            <a href="{{ route('map') }}" class="btn btn-success btn-lg">Explorer la carte</a>
                            <a href="#" class="btn btn-outline-light btn-lg">Publier un produit</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide1.jpg') }}" class="d-block w-100 object-fit-cover"
                        style="height: 500px;" alt="...">
                    <div
                        class="carousel-caption d-flex flex-column justify-content-center align-items-center top-0 bottom-0 start-0 end-0 text-center">
                        <h1 class="fw-bold">Trouvez et achetez des produits frais...</h1>
                        <div class="mt-3">
                            <a href="{{ route('map') }}" class="btn btn-success btn-lg">Explorer la carte</a>
                            <a href="#" class="btn btn-outline-light btn-lg">Publier un produit</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

    </section>

    <!-- Featured Products Section -->
    <section class="py-5 bg-light">
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
                            @foreach ($categories as $category)
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
                            <li><a class="dropdown-item" href="{{ route('home', ['sort' => 'price_asc']) }}">Croissant</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ route('home', ['sort' => 'price_desc']) }}">Décroissant</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            id="distanceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Distance <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="distanceDropdown">
                            <li><a class="dropdown-item" href="{{ route('home', ['distance' => 5]) }}">&lt; 5 km</a></li>
                            <li><a class="dropdown-item" href="{{ route('home', ['distance' => 10]) }}">&lt; 10 km</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('home', ['distance' => 20]) }}">&lt; 20 km</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('home', ['distance' => 'all']) }}">Tous</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="position-relative">
                <button class="btn btn-light position-absolute top-50 start-0 translate-middle-y shadow-sm"
                    id="prevProduct" aria-label="Produit précédent">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <div class="row" id="productsContainer">
                    @forelse($featuredProducts as $product)
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/products/default.jpg') }}"
                                    class="card-img-top" alt="{{ $product->name }}"
                                    style="height: 200px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text fw-bold">{{ number_format($product->price, 2) }}€ /
                                        {{ $product->unit }}</p>
                                    <p class="text-muted small">{{ $product->user->address }}</p>
                                    <a href="{{ route('products.show', $product->slug) }}"
                                        class="btn btn-outline-success btn-sm mt-2">
                                        Voir le produit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">Aucun produit disponible pour le moment.</p>
                        </div>
                    @endforelse
                </div>

                <button class="btn btn-light position-absolute top-50 end-0 translate-middle-y shadow-sm" id="nextProduct"
                    aria-label="Produit suivant">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            @if ($featuredProducts->count() > 4)
                <div class="text-center mt-3">
                    @for ($i = 0; $i < ceil($featuredProducts->count() / 4); $i++)
                        <button
                            class="btn btn-sm {{ $i === 0 ? 'btn-primary' : 'btn-outline-secondary' }} mx-1 rounded-circle pagination-dot"
                            style="width: 12px; height: 12px;" data-page="{{ $i }}"></button>
                    @endfor
                </div>
            @endif
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
                                <span class="text-muted fst-italic">Ce service est excellent! Grâce à leur
                                    professionnalisme, j'ai pu
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
                                <span class="text-muted fst-italic">Un accompagnement de qualité! Une équipe à l'écoute et
                                    des
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
                                <span class="text-muted fst-italic">Une expérience incroyable! J'ai pu améliorer mes ventes
                                    et
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
                if (currentPage < Math.ceil(productsContainer.querySelectorAll('.col-md-3').length /
                        productsPerPage) - 1) {
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
