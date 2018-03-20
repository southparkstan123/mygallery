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
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['Content-Type', 'X-Requested-With','Origin'],
    'allowedMethods' => ['GET', 'POST', 'PUT',  'DELETE', 'OPTIONS'],
    'exposedHeaders' => [],
    'maxAge' => 3600,

];
