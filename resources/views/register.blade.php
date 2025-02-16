<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Nom</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="password_confirmation">Confirmation du mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
        <br>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>

