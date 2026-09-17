<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Search Engine Indexing
    |--------------------------------------------------------------------------
    |
    | Development and testing environments stay hidden by default. Production
    | can be explicitly disabled with SEO_INDEXING_ENABLED=false when needed.
    |
    */
    'indexing_enabled' => env(
        'SEO_INDEXING_ENABLED',
        env('APP_ENV', 'production') === 'production',
    ),
];
