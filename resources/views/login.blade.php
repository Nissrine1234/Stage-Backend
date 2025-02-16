<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
