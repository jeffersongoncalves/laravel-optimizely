<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Optimizely API Token
    |--------------------------------------------------------------------------
    |
    | A personal access token generated under Profile > API Access in your
    | Optimizely account. Sent as a Bearer token on every request.
    |
    */
    'api_key' => env('OPTIMIZELY_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | Root of the Optimizely REST API v2.
    |
    */
    'base_url' => env('OPTIMIZELY_BASE_URL', 'https://api.optimizely.com/v2'),

    /*
    |--------------------------------------------------------------------------
    | Default Project
    |--------------------------------------------------------------------------
    |
    | Used by every resource that requires a project id when none is given.
    |
    */
    'project_id' => env('OPTIMIZELY_PROJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Default Pagination Per Page
    |--------------------------------------------------------------------------
    |
    | Used as the default "per_page" for list endpoints when none is given.
    |
    */
    'default_per_page' => env('OPTIMIZELY_DEFAULT_PER_PAGE', 25),

];
