<?php

return [
    'paths' => ['api/*'], // Autorise CORS sur toutes les routes API
    'allowed_methods' => ['*'], // Autorise toutes les méthodes (GET, POST, PUT, DELETE, etc.)
    'allowed_origins' => ['*'], // Autorise toutes les origines (mettre un domaine spécifique pour + de sécurité)
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Autorise tous les headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Passe à true si tu utilises des cookies ou tokens sécurisés
];
?>