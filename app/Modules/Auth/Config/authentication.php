<?php

return [
    'route_prefix' => env('AUTH_ROUTE_PREFIX', 'api/v1/auth'),
    'middleware' => ['api', 'force.json'],
];
