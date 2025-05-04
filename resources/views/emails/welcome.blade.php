<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenue sur AgriCarte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2E7D32;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.jpg') }}" alt="AgriCarte Logo" class="logo">
        <h1>Bienvenue sur AgriCarte</h1>
    </div>

    <div class="content">
        <p>Bonjour {{ $user->name }},</p>
        
        <p>Nous sommes ravis de vous accueillir sur AgriCarte, la plateforme qui connecte les producteurs locaux avec les consommateurs.</p>

        @if($user->role === 'farmer')
            <p>En tant que producteur, vous pouvez maintenant :</p>
            <ul>
                <li>Gérer vos produits</li>
                <li>Recevoir des commandes</li>
                <li>Interagir avec vos clients</li>
            </ul>
        @else
            <p>En tant que client, vous pouvez maintenant :</p>
            <ul>
                <li>Découvrir des produits locaux</li>
                <li>Passer des commandes</li>
                <li>Suivre vos achats</li>
            </ul>
        @endif

        <p>Pour commencer, connectez-vous à votre compte :</p>
        
        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="button">Se connecter</a>
        </div>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        <p>&copy; {{ date('Y') }} AgriCarte. Tous droits réservés.</p>
    </div>
</body>
</html> 