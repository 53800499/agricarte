<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Agricarte</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2E7D32;
            --primary-light: #4CAF50;
            --primary-dark: #1B5E20;
            --secondary-color: #FF9800;
            --secondary-light: #FFB74D;
            --secondary-dark: #F57C00;
            --success-color: #4CAF50;
            --info-color: #2196F3;
            --warning-color: #FFC107;
            --danger-color: #F44336;
            --light-color: #F5F5F5;
            --dark-color: #212121;
            --gray-color: #757575;
            --border-color: #E0E0E0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8F9FA;
            color: var(--dark-color);
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            color: #fff;
            transition: all 0.3s;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1.5rem;
            margin: 0.2rem 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .sidebar .nav-link span {
            font-size: 0.9rem;
        }

        .sidebar-heading {
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.5);
        }

        .main-content {
            width: calc(100% - 250px);
            min-height: 100vh;
            margin-left: 250px;
            transition: all 0.3s;
            background-color: #F8F9FA;
        }

        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 998;
            padding: 1rem;
        }

        .navbar .dropdown-toggle {
            color: var(--dark-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar .dropdown-toggle img {
            border: 2px solid var(--primary-color);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0 !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: var(--secondary-dark);
            border-color: var(--secondary-dark);
            transform: translateY(-1px);
        }

        .table {
            background-color: #fff;
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--light-color);
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        .table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        .alert {
            border-radius: 1rem;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
        }

        .badge {
            padding: 0.5em 0.8em;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            color: var(--primary-color);
            border: none;
            margin: 0 2px;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .dropdown-menu {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--light-color);
            transform: translateX(5px);
        }

        .modal-content {
            border: none;
            border-radius: 1rem;
        }

        .modal-header {
            background-color: var(--light-color);
            border-bottom: 1px solid var(--border-color);
            border-radius: 1rem 1rem 0 0;
            padding: 1.5rem;
        }

        .toast {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .toast-header {
            background-color: var(--light-color);
            border-bottom: 1px solid var(--border-color);
            border-radius: 1rem 1rem 0 0;
            padding: 1rem 1.5rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
                position: fixed;
                height: 100vh;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                width: 100%;
                margin-left: 0;
            }


            .main-content.active {
                margin-left: 250px;
            }

            .navbar {
                padding: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.jpg') }}" alt="AgriCarte Logo" class="rounded-circle me-2"
                        style="width: 40px; height: 40px; object-fit: cover;">
                    <span class="fw-bold text-white">AgriCarte</span>
                </a>
            </div>

            <ul class="list-unstyled components">
                <!-- Nav Item - Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') || request()->routeIs('farmers.dashboard') ? 'active bg-custom' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Gestion
                </div>

                @if (auth()->user()->role === 'admin')
                    <!-- Nav Item - Products (Admin) -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active bg-custom' : '' }}"
                            href="{{ route('admin.products.index') }}">
                            <i class="fas fa-fw fa-box"></i>
                            <span>Produits</span>
                        </a>
                    </li>

                    <!-- Nav Item - Farmers -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.farmers.index') ? 'active bg-custom' : '' }}"
                            href="{{ route('admin.farmers.index') }}">
                            <i class="fas fa-fw fa-user-tie"></i>
                            <span>Producteurs</span>
                        </a>
                    </li>

                    <!-- Nav Item - Categories -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active bg-custom' : '' }}"
                            href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-fw fa-tags"></i>
                            <span>Catégories</span>
                        </a>
                    </li>

                    <!-- Nav Item - Orders -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active bg-custom' : '' }}"
                            href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-fw fa-shopping-cart"></i>
                            <span>Commandes</span>
                        </a>
                    </li>

                    <!-- Nav Item - Users -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active bg-custom' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <i class="fas fa-fw fa-users"></i>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                @elseif(auth()->user()->role === 'farmer')
                    <!-- Nav Item - Products (Farmer) -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('farmer.products.index') ? 'active bg-custom opacity-75' : '' }}"
                            href="{{ route('farmer.products.index') }}">
                            <i class="fas fa-fw fa-box"></i>
                            <span>Produits</span>
                        </a>
                    </li>

                    <!-- Nav Item - Orders (Farmer) -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('farmer.orders.index') ? 'active bg-custom' : '' }}"
                            href="{{ route('farmer.orders.index') }}">
                            <i class="fas fa-fw fa-shopping-cart"></i>
                            <span>Mes Commandes</span>
                        </a>
                    </li>
                @endif

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Mon compte
                </div>

                <!-- Nav Item - Profile -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile') ? 'active bg-custom' : '' }}"
                        href="{{ route('profile') }}">
                        <i class="fas fa-fw fa-user"></i>
                        <span>Profil</span>
                    </a>
                </li>

                @if (Auth::user()->role === 'admin')
                    <!-- Nav Item - Settings -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('settings') ? 'active bg-custom' : '' }}"
                            href="{{ route('settings') }}">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>


        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="btn btn-link d-lg-none" type="button" id="sidebarCollapse">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="d-flex align-items-center ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle" type="button" id="userDropdown"
                                data-bs-toggle="dropdown">
                                <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('images/profile.jpg') }}"
                                    alt="{{ Auth::user()->name }}" class="rounded-circle me-2" width="32"
                                    height="32">
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile', Auth::user()->id) }}">
                                        <i class="fas fa-user me-2"></i>Profil
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2 text-danger"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Toggle sidebar on mobile
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('.sidebar').toggleClass('active');
                $('.main-content').toggleClass('active');
            });

            // Active sidebar item
            const currentPath = window.location.pathname;
            $('.nav-link').each(function() {
                if ($(this).attr('href') === currentPath) {
                    $(this).addClass('active');
                }
            });

            // Smooth scroll for dropdown items
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();
                const target = $(this).attr('href');
                if (target) {
                    window.location.href = target;
                }
            });
        });

        // Toast notifications
        @if (session('success'))
            showToast('success', '{{ session('success') }}');
        @endif

        @if (session('error'))
            showToast('error', '{{ session('error') }}');
        @endif

        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            toast.addEventListener('hidden.bs.toast', function() {
                document.body.removeChild(toast);
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
