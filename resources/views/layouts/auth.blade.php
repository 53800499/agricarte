<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Agricarte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
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
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
        }
        .auth-sidebar {
            color: white;
            padding: 40px;
            height: 100%;
        }
        .auth-sidebar h3 {
            color: white;
            margin: auto;
        }
        .auth-form {
            padding: 40px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ced4da;
        }
        .btn-success {
            background-color: #1B4332;
            border: none;
            padding: 12px;
            border-radius: 8px;
        }
        .btn-success:hover {
            background-color: #143728;
        }
        .auth-image {
            width: 40%;
            margin-bottom: 10px;
        }
        .features-list, .benefits-list {
            list-style: none;
            padding: 0;
        }
        .features-list li, .benefits-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .features-list li i, .benefits-list li i {
            margin-right: 10px;
            color: #4CAF50;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="auth-card m-auto">
                <div class="row g-0">
                    <div class="col-md-5 auth-sidebar bg-success">
                        @yield('sidebar')
                    </div>
                    <div class="col-md-7">
                        <div class="auth-form align-items-center">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
