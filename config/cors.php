<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [

        // Local frontend
        'http://localhost:5173',

        // Production frontend (Vercel)
        'https://prescripto-frontend-gamma.vercel.app',

        // Production backend (Railway) - IMPORTANT
        'https://prescriptobackend-production-9ecf.up.railway.app',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
