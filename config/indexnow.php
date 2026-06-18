<?php

return [
    'enabled' => env('INDEXNOW_ENABLED', true),
    'key' => env('INDEXNOW_KEY'),
    'host' => parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST),
];
