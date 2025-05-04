@extends('layouts.app')

@section('title', 'Accueil')

@push('styles')
<style>
    .hero-section {
        min-height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/hero-bg.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -76px;
    }

    .hero-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
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
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url('{{ asset('images/hero-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 hero-content animate-on-scroll">
                    <h1 class="display-4 fw-bold mb-4">Découvrez les produits frais de nos agriculteurs locaux</h1>
                    <p class="lead mb-5">Connectez-vous directement avec les producteurs de votre région et profitez de produits frais et de qualité.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('products') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-shopping-basket me-2"></i>Voir les produits
                        </a>
                        <a href="{{ route('map') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-map-marker-alt me-2"></i>Explorer la carte
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Pourquoi choisir AgriCarte ?</h2>
                <p class="lead text-muted">Une expérience unique pour soutenir l'agriculture locale</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-leaf fa-2x text-success"></i>
                        </div>
                        <h3 class="h4 mb-3">Produits frais</h3>
                        <p class="text-muted">Accédez à des produits frais et de saison, directement des producteurs locaux.</p>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-truck fa-2x text-success"></i>
                        </div>
                        <h3 class="h4 mb-3">Circuit court</h3>
                        <p class="text-muted">Réduisez les intermédiaires et soutenez l'économie locale.</p>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-certificate fa-2x text-success"></i>
                        </div>
                        <h3 class="h4 mb-3">Qualité garantie</h3>
                        <p class="text-muted">Des produits de qualité, cultivés dans le respect de l'environnement.</p>
                    </div>
                </div>
            </div>
        </div>
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
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                        <div class="category-badge">{{ $product->category->name }}</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-success">{{ number_format($product->price, 2) }} €</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('favorites.toggle', $product) }}" class="btn btn-outline-danger favorite-toggle">
                                        <i class="{{ auth()->check() && auth()->user()->favorites->contains($product) ? 'fas' : 'far' }} fa-heart"></i>
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
                        <img src="{{ asset('images/testimonial-1.jpg') }}" class="testimonial-image" alt="Client 1">
                        <div class="card-body">
                            <p class="card-text">"Grâce à AgriCarte, je peux facilement trouver des produits frais et locaux. Une vraie révolution dans ma façon de consommer !"</p>
                            <h5 class="card-title mb-0">Marie D.</h5>
                            <small class="text-muted">Client depuis 2022</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll">
                    <div class="card testimonial-card h-100">
                        <img src="{{ asset('images/testimonial-2.jpg') }}" class="testimonial-image" alt="Client 2">
                        <div class="card-body">
                            <p class="card-text">"En tant que producteur, AgriCarte m'a permis de développer ma clientèle locale. Une plateforme simple et efficace !"</p>
                            <h5 class="card-title mb-0">Jean P.</h5>
                            <small class="text-muted">Producteur depuis 2021</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll">
                    <div class="card testimonial-card h-100">
                        <img src="{{ asset('images/testimonial-3.jpg') }}" class="testimonial-image" alt="Client 3">
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
    // Animation on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.feature-card, .product-card, .testimonial-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }, {
            threshold: 0.1
        });

        elements.forEach(element => {
            observer.observe(element);
        });
    });
</script>
@endpush
