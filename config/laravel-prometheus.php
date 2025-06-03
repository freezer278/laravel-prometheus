<?php

return [
    // namespace is used to prefix the prometheus metrics, can be any string value
    'namespace' => env('PROMETHEUS_NAMESPACE', ''),
    // type of storage for keeping metrics data (known values: redis, in_memory, apcu). redis - works best now.
    'storage_type' => env('PROMETHEUS_STORAGE_TYPE', 'redis'),
    // redis connection for prometheus metrics (used only when storage_type == redis)
    'redis_connection' => env('PROMETHEUS_REDIS_CONNECTION', 'default'),

    'route_url' => 'metrics',
    'route_middleware' => [
        \VMorozov\Prometheus\Middleware\AllowIpsMiddleware::class,
    ],

    'default_metrics_enabled' => true,
];
