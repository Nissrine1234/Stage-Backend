<!DOCTYPE html>
<html>
<head>
    <title>Récupération du mot de passe</title>
</head>
<body>
    <h1>Récupération du mot de passe</h1>
    <form method="POST" action="{{ route('forgot-password') }}">
        @csrf
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Récupérer le mot de passe</button>
    </form>
</body>
</html>
