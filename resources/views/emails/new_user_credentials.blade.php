<!DOCTYPE html>
<html>
<head>
    <title>Vos identifiants de connexion temporaires</title>
</head>
<body>
    <h1>Bienvenue, {{ $user->prenom }} {{ $user->nom }} !</h1>

    <p>Votre compte sur notre plateforme a été créé avec succès. Voici vos informations de connexion temporaires :</p>

    <table style="border: 1px solid #ccc; border-collapse: collapse; width: 100%; max-width: 400px; margin: 20px 0;">
        <tr>
            <td style="padding: 8px; border: 1px solid #ccc; background-color: #f7f7f7;"><strong>Email :</strong></td>
            <td style="padding: 8px; border: 1px solid #ccc;">{{ $user->email }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ccc; background-color: #f7f7f7;"><strong>Mot de passe temporaire :</strong></td>
            <td style="padding: 8px; border: 1px solid #ccc;">{{ $rawPassword }}</td>
        </tr>
    </table>

    <p>Nous vous recommandons vivement de <a href="{{ url('/login') }}">vous connecter</a> et de changer ce mot de passe immédiatement pour des raisons de sécurité.</p>

    <p>Cordialement,<br>L'équipe Support</p>
</body>
</html>