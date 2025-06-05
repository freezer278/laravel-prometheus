# Configuration

This document provides detailed information about the configuration options available in the Laravel Prometheus package.

## Overview

The Laravel Prometheus package can be configured using the `laravel-prometheus.php` configuration file. This file is published to your application's `config` directory when you run the `vendor:publish` command.

```bash
php artisan vendor:publish --provider="VMorozov\Prometheus\PrometheusServiceProvider"
```

## Configuration Options

### Namespace

```php
'namespace' => env('PROMETHEUS_NAMESPACE', env('APP_NAME', 'laravel')),
```

The `namespace` option is used to prefix all prometheus metrics. This helps to avoid naming conflicts when multiple applications are sending metrics to the same Prometheus server.

By default, it uses the `PROMETHEUS_NAMESPACE` environment variable, falling back to the `APP_NAME` environment variable, and finally to 'laravel' if neither is set.

Example metric names with different namespaces:
- `laravel_request_duration_histogram_ms`
- `my_app_request_duration_histogram_ms`

### Storage Type

```php
'storage_type' => env('PROMETHEUS_STORAGE_TYPE', 'redis'),
```

The `storage_type` option determines how the metrics data is stored. The package supports three storage types:

- `redis` - Stores metrics in Redis (recommended for production)
- `in_memory` - Stores metrics in memory (lost when the process ends)
- `apcu` - Stores metrics using APCu

The default is `redis`, which is the most robust option for production environments.

### Redis Connection

```php
'redis_connection' => env('PROMETHEUS_REDIS_CONNECTION', 'default'),
```

The `redis_connection` option specifies which Redis connection to use for storing metrics data. This option is only used when `storage_type` is set to `redis`.

By default, it uses the `PROMETHEUS_REDIS_CONNECTION` environment variable, falling back to 'default' if not set.

### Route URL

```php
'route_url' => 'metrics',
```

The `route_url` option specifies the URL path where the metrics will be exposed. By default, this is set to 'metrics', which means you can access your metrics at `https://your-app.com/metrics`.

### Route Middleware

```php
'route_middleware' => [
    VMorozov\Prometheus\Middleware\AllowIpsMiddleware::class,
],
```

The `route_middleware` option specifies the middleware that will be applied to the metrics endpoint. By default, it includes the `AllowIpsMiddleware`, which restricts access to the metrics endpoint based on IP address.

You can add your own middleware to this array to implement custom authentication or other functionality.

### Default Metrics Enabled

```php
'default_metrics_enabled' => true,
```

The `default_metrics_enabled` option determines whether the default metrics (request duration histogram and queue size gauge) are enabled. Set this to `false` if you only want to use custom metrics.

### On-Demand Metric Collectors

```php
'on_demand_metric_collectors' => [
    [
        'class' => VMorozov\Prometheus\Collectors\DefaultMetrics\QueueSizeGaugeOnDemandMetricCollector::class,
        'configs' => [
            'connections' => ['redis'],
            'queues' => ['default'],
        ],
    ],
],
```

The `on_demand_metric_collectors` option is an array of collectors that will be called when the metrics endpoint is accessed. Each collector is defined by:

- `class` - The fully qualified class name of the collector
- `configs` - An array of configuration options that will be passed to the collector's `collect` method

By default, it includes the `QueueSizeGaugeOnDemandMetricCollector`, which collects the size of the specified queues.

You can add your own collectors to this array to collect custom metrics. See the [OnDemandMetricsCollectors](on-demand-metrics-collectors.md) documentation for more information.

## Environment Variables

The package supports the following environment variables:

- `PROMETHEUS_NAMESPACE` - The namespace to use for metrics (default: `APP_NAME` or 'laravel')
- `PROMETHEUS_STORAGE_TYPE` - The storage type to use (default: 'redis')
- `PROMETHEUS_REDIS_CONNECTION` - The Redis connection to use (default: 'default')

You can set these variables in your `.env` file to override the default configuration.

## Example Configuration

Here's an example of a complete configuration file with custom settings:

```php
<?php

return [
    'namespace' => env('PROMETHEUS_NAMESPACE', 'my_app'),
    'storage_type' => env('PROMETHEUS_STORAGE_TYPE', 'redis'),
    'redis_connection' => env('PROMETHEUS_REDIS_CONNECTION', 'prometheus'),

    'route_url' => 'prometheus/metrics',
    'route_middleware' => [
        VMorozov\Prometheus\Middleware\AllowIpsMiddleware::class,
        \App\Http\Middleware\CustomAuthMiddleware::class,
    ],

    'default_metrics_enabled' => true,

    'on_demand_metric_collectors' => [
        [
            'class' => VMorozov\Prometheus\Collectors\DefaultMetrics\QueueSizeGaugeOnDemandMetricCollector::class,
            'configs' => [
                'connections' => ['redis', 'sqs'],
                'queues' => ['default', 'emails', 'notifications'],
            ],
        ],
        [
            'class' => \App\Collectors\DatabaseConnectionsGaugeOnDemandMetricCollector::class,
            'configs' => [
                'connections' => ['mysql', 'pgsql'],
            ],
        ],
    ],
];
```

This configuration:
- Uses 'my_app' as the default namespace
- Uses Redis for storage
- Uses a custom Redis connection named 'prometheus'
- Exposes metrics at the URL 'prometheus/metrics'
- Adds a custom middleware to the metrics endpoint
- Enables the default metrics
- Configures the queue size collector to monitor multiple connections and queues
- Adds a custom collector for database connections
