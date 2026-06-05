<?php

return [
    'route_prefix' => env('CATALOGS_ROUTE_PREFIX', 'api/v1/catalogs'),
    'middleware' => ['api', 'force.json'],
];
