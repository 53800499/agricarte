<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenue dans notre newsletter AgriCarte</title>
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
            padding: 30px;
            border-radius: 10px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
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
        <h1>Bienvenue dans notre newsletter !</h1>
    </div>

    <div class="content">
        <p>Bonjour,</p>
        <p>Merci de vous être inscrit à notre newsletter. Vous recevrez régulièrement des informations sur :</p>
        <ul>
            <li>Les nouveaux produits disponibles</li>
            <li>Les offres spéciales</li>
            <li>Les événements locaux</li>
            <li>Les actualités de nos producteurs</li>
        </ul>
        <p>Pour commencer à explorer nos produits, visitez notre site :</p>
        <p style="text-align: center;">
            <a href="{{ route('home') }}" class="button">Visiter AgriCarte</a>
        </p>
    </div>

    <div class="footer">
        <p>Si vous ne souhaitez plus recevoir nos emails, vous pouvez vous désinscrire à tout moment en cliquant sur le lien en bas de nos emails.</p>
        <p>&copy; {{ date('Y') }} AgriCarte. Tous droits réservés.</p>
    </div>
</body>
</html>
