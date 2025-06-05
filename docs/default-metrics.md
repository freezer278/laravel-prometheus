# Default Metrics

This document provides an overview of the default metrics that are collected by the Laravel Prometheus package.

## Overview

The Laravel Prometheus package includes two default metrics:

1. **Request Duration Histogram** - Measures the duration of HTTP requests
2. **Queue Size Gauge** - Measures the size of Laravel queues

These metrics are enabled by default but can be disabled by setting `default_metrics_enabled` to `false` in the configuration file.

## Request Duration Histogram

The Request Duration Histogram metric measures the duration of HTTP requests in milliseconds. It is collected automatically for all requests to your application (except for the metrics endpoint itself).

### Metric Details

- **Name**: `request_duration_histogram_ms`
- **Type**: Histogram
- **Help Text**: "requests durations in ms"
- **Labels**:
  - `url` - The path of the request
  - `method` - The HTTP method of the request (GET, POST, etc.)

### Buckets

The histogram uses the following buckets (in milliseconds):

```
[10.0, 50.0, 100.0, 150.0, 200.0, 250.0, 300.0, 500.0, 750.0, 1000.0, 2500.0, 5000.0, 7500.0, 10000.0, 30000.0, 60000.0]
```

This allows you to see the distribution of request durations, from very fast requests (10ms) to very slow requests (60 seconds).

### Implementation

The Request Duration Histogram metric is implemented by:

1. `RequestDurationHistogramMetric` - The metric class that defines the metric
2. `RequestDurationHistogramMetricCollector` - The collector that calculates the request duration and adds it to the histogram
3. `CollectRequestDurationMetric` - The middleware that hooks into the request lifecycle and calls the collector

The middleware is automatically registered by the package and will collect metrics for all requests to your application, except for the metrics endpoint itself.

## Queue Size Gauge

The Queue Size Gauge metric measures the size of Laravel queues. It is collected on-demand when the metrics endpoint is called.

### Metric Details

- **Name**: `queue_size_gauge`
- **Type**: Gauge
- **Help Text**: "current queue size"
- **Labels**:
  - `connection_name` - The name of the queue connection (e.g., "redis")
  - `queue_name` - The name of the queue (e.g., "default")

### Implementation

The Queue Size Gauge metric is implemented by:

1. `QueueSizeGaugeMetric` - The metric class that defines the metric
2. `QueueSizeGaugeOnDemandMetricCollector` - The on-demand collector that gets the queue size and sets the gauge value

The collector is registered in the configuration file and will be called when the metrics endpoint is accessed:

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

You can customize which connections and queues are monitored by modifying the `connections` and `queues` arrays in the configuration.

## Viewing Metrics

To view the collected metrics, access the `/metrics` endpoint of your application (or the custom URL you've configured). The metrics will be displayed in the Prometheus exposition format, which can be scraped by a Prometheus server.

Example output:

```
# HELP laravel_request_duration_histogram_ms requests durations in ms
# TYPE laravel_request_duration_histogram_ms histogram
laravel_request_duration_histogram_ms_bucket{url="api/users",method="GET",le="10"} 0
laravel_request_duration_histogram_ms_bucket{url="api/users",method="GET",le="50"} 2
laravel_request_duration_histogram_ms_bucket{url="api/users",method="GET",le="100"} 5
...
laravel_request_duration_histogram_ms_bucket{url="api/users",method="GET",le="60000"} 10
laravel_request_duration_histogram_ms_bucket{url="api/users",method="GET",le="+Inf"} 10
laravel_request_duration_histogram_ms_count{url="api/users",method="GET"} 10
laravel_request_duration_histogram_ms_sum{url="api/users",method="GET"} 1234.56

# HELP laravel_queue_size_gauge current queue size
# TYPE laravel_queue_size_gauge gauge
laravel_queue_size_gauge{connection_name="redis",queue_name="default"} 42
```

## Extending Default Metrics

If you want to extend or modify the default metrics, you can:

1. Create your own metric classes that extend the default ones
2. Create your own collectors that use your custom metrics
3. Register your custom collectors in the configuration file

For example, you might want to add additional labels to the request duration histogram or change the buckets. See the [Custom Metrics](custom-metrics.md) and [OnDemandMetricsCollectors](on-demand-metrics-collectors.md) documentation for more information on how to create custom metrics and collectors.
