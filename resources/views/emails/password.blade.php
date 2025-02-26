<!DOCTYPE html>
<html>
<head>
    <title>Votre compte est activé</title>
</head>
<body>
    <p>Bonjour,</p>
    <p>Votre demande d'inscription a été acceptée ! Voici vos identifiants :</p>
    <p><strong>Email :</strong> {{ $email }}</p>
    <p><strong>Mot de passe :</strong> {{ $password }}</p>
    <p>Veuillez vous connecter et changer votre mot de passe dès que possible.</p>
    <p>Cordialement,</p>
    <p>L'équipe {{ config('app.name') }}</p>
</body>
</html>
