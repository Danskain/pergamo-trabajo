<?php

return [
    'route_prefix' => env('ACCOUNTING_ROUTE_PREFIX', 'api/v1/accounting'),
    'middleware' => ['api', 'force.json'],
];
