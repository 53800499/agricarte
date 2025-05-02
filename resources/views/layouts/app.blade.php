<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriCarte - @yield('title', 'Produits frais directement des agriculteurs')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .navbar-brand img {
            height: 40px;
        }

        .hero-section {
            background-image: url('/images/slide1.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            position: relative;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-outline-success {
            color: #28a745;
            border-color: #28a745;
        }

        .product-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .filter-section {
            margin-bottom: 30px;
        }
    </style>
    @yield('styles')
    <link rel="stylesheet" href="/app.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <div class="text-center">
                    <img src="/images/logo.jpg" alt="AgriCarte Logo" class="rounded-circle">
                </div>
                <div>AgriCarte</div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}"
                            href="{{ route('products') }}">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}"
                            href="{{ route('map') }}">Carte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                            href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li class="nav-item ms-2">
                        @auth
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Se Déconnecter</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success">Se Connecter</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light py-5 mt-5">

        <!-- Footer Links -->
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4 text-center text-md-start mb-4 mb-md-0">
                    <h5 class="mb-3">À propos</h5>
                    <p class="text-muted">Nous sommes une entreprise dédiée à fournir des solutions innovantes pour nos
                        clients.</p>
                </div>
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <h5 class="mb-3">Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}"
                                class="text-decoration-none text-muted">Accueil</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Nos services</a>
                        </li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Témoignages</a>
                        </li>
                        <li><a href="{{ route('contact') }}" class="text-decoration-none text-muted">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 text-center">
                    <h5 class="mb-3">Contact</h5>
                    <p class="text-muted mb-1">123 Rue Exemple, Ville, Pays</p>
                    <p class="text-muted mb-1">Téléphone : +33 1 23 45 67 89</p>
                    <p class="text-muted">Email : contact@exemple.com</p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="container text-center pt-4 border-top">
            <p class="text-muted mb-1">© 2024 Nom de l'entreprise. Tous droits réservés.</p>
            <p class="text-muted mb-0">
                <a href="#" class="text-decoration-none text-muted">Mentions légales</a> |
                <a href="#" class="text-decoration-none text-muted">Politique de confidentialité</a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
