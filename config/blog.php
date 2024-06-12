<?php

return [
    'seo' => [
        'meta' => [
            'title' => 'Filament Blog',
            'description' => 'This is filament blog seo meta description',
            'keywords' => [],
        ],
    ],
    'recaptcha' => [
        'enabled' => false, // true or false
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],
];
