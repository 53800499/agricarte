<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Agricarte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #539778FF;
            --secondary-color: #2D6A4F;
            --accent-color: #40916C;
            --text-color: #333;
            --light-color: #f8f9fa;
            --success-color: #4CAF50;
        }

        body {
            background: linear-gradient(135deg, var(--light-color) 0%, #ffffff 100%);
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-5px);
        }

        .auth-sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 40px;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .auth-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/images/pattern.png');
            opacity: 0.1;
            z-index: 0;
        }

        .auth-sidebar > * {
            position: relative;
            z-index: 1;
        }

        .auth-sidebar h3 {
            color: white;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .auth-form {
            padding: 40px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(64, 145, 108, 0.25);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #e9ecef;
            border-right: none;
            background-color: white;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-success {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-success:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-outline-light {
            border-width: 2px;
        }

        .btn-outline-light:hover {
            transform: translateY(-2px);
        }

        .auth-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 4px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease;
        }

        .auth-image:hover {
            transform: scale(1.05);
        }

        .features-list, .benefits-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }

        .features-list li, .benefits-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }

        .features-list li i, .benefits-list li i {
            margin-right: 12px;
            color: var(--success-color);
            font-size: 1.2rem;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }

        .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .alert {
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 1.5rem;
        }

        @media (max-width: 768px) {
            .auth-sidebar {
                border-radius: 20px 20px 0 0;
                padding: 30px;
            }

            .auth-form {
                padding: 30px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="auth-card m-auto animate__animated animate__fadeIn">
                <div class="row g-0">
                    <div class="col-md-5 auth-sidebar">
                        @yield('sidebar')
                    </div>
                    <div class="col-md-7">
                        <div class="auth-form">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des éléments de la liste au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.features-list li, .benefits-list li');
            items.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Amélioration de l'expérience utilisateur pour les formulaires
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Chargement...';
                }
            });
        });

        // Affichage/masquage du mot de passe
        const passwordInputs = document.querySelectorAll('input[type="password"]');
        passwordInputs.forEach(input => {
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'btn btn-outline-secondary';
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
            toggleBtn.style.borderRadius = '0 10px 10px 0';

            input.parentElement.appendChild(toggleBtn);
            input.style.borderRadius = '10px 0 0 10px';

            toggleBtn.addEventListener('click', () => {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                toggleBtn.innerHTML = `<i class="fas fa-eye${type === 'password' ? '' : '-slash'}"></i>`;
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
