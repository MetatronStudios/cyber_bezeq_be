<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */

    'supportsCredentials' => false,
    'allowedOrigins' => ['https://elbitsecurity.cybergame.co.il'],
    'allowedOriginsPatterns' => env('APP_ENV') === 'local' ? ['/^http:\/\/localhost(:[0-9]+)?$/', '/^http:\/\/192\.168\.\d+\.\d+(:[0-9]+)?$/'] : [],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
