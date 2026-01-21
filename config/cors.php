<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // Local development
        'http://localhost:5173',

        // Your frontend (Vercel)
        'https://prescripto-frontend-gamma.vercel.app',

        // Your backend (Railway)
        'https://prescriptocopy-production.up.railway.app',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // IMPORTANT: token-based auth
    'supports_credentials' => false,
];
