<?php

return [
    'facebook' => [
        'client_id' => $_ENV['FACEBOOK_CLIENT_ID'] ?? getenv('FACEBOOK_CLIENT_ID') ?? '',
        'client_secret' => $_ENV['FACEBOOK_CLIENT_SECRET'] ?? getenv('FACEBOOK_CLIENT_SECRET') ?? '',
        'redirect_uri' => $_ENV['FACEBOOK_REDIRECT_URI'] ?? getenv('FACEBOOK_REDIRECT_URI') ?? '',
    ],
    'google' => [
        'client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? getenv('GOOGLE_CLIENT_ID') ?? '',
        'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? getenv('GOOGLE_CLIENT_SECRET') ?? '',
        'redirect_uri' => $_ENV['GOOGLE_REDIRECT_URI'] ?? getenv('GOOGLE_REDIRECT_URI') ?? '',
    ],
    'jwt_secret_key' => $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET') ?? 'change-me',
    'jwt_expiration' => (int)($_ENV['JWT_EXPIRATION'] ?? getenv('JWT_EXPIRATION') ?? 3600),
];