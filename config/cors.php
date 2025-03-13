<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
    ],

    'allowed_methods' => [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'
    ],

    'allowed_origins' => [
        'https://testdomain.com',
    ],

    'allowed_origins_patterns' => [
        // 'https://*.example.com',
    ],

    'allowed_headers' => [
        'Content-Type', 'X-Requested-With', 'Authorization', 'Accept', 'Origin',
        'X-Custom-Header', 'X-XSRF-TOKEN'
    ],

    'exposed_headers' => [
        'Authorization', 'X-Total-Count', 'X-Page-Count',
    ],

    'max_age' => 3600,

    'supports_credentials' => true,

];
