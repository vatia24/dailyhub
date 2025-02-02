<?php

return [
    'facebook' => [
        'client_id' => 'your-facebook-client-id',
        'client_secret' => 'your-facebook-client-secret',
        'redirect_uri' => 'https://yourapp.com/auth/facebook/callback',
    ],
    'google' => [
        'client_id' => 'your-google-client-id',
        'client_secret' => 'your-google-client-secret',
        'redirect_uri' => 'https://yourapp.com/auth/google/callback',
    ],
    'jwt_secret_key' => 'SDF%^212HSF1', // Securely generated secret key
    'jwt_expiration' => 3600, // Token expiration time (in seconds)
];