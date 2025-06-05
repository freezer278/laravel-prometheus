# On Demand Metrics Collectors Development and Usage

This document provides information on how to develop and use custom On Demand Metrics Collectors.

## What are On Demand Metrics Collectors?

On Demand Metrics Collectors are classes that collect metrics only when the metrics API endpoint is called. This is useful for metrics that are expensive to collect or that don't need to be updated on every request.

The package includes a default OnDemandMetricsCollector for queue sizes:
- `QueueSizeGaugeOnDemandMetricCollector` - Collects the size of specified queues

## Creating Custom On Demand Metrics Collectors

To create a custom OnDemandMetricsCollector, you need to:

1. Create a class that implements the `VMorozov\Prometheus\Collectors\Interfaces\OnDemandMetricCollector` interface
2. Implement the `collect(array $configs)` method. It is a good idea to validate the configs passed to the method and add doc block with configs array shape.
    ```php
        /**
         * @param array{some_key_1: string ,some_key_2: string[]} $configs
         * @return void
         */
        public function collect(array $configs): void
        {
            // todo: your collector logic goes here
        }
    
    ```
3. Register your collector in the configuration file in the `on_demand_metric_collectors` array
    ```php
    'on_demand_metric_collectors' => [
            // ... some default collectors
            [
                'class' => YourCustomOnDemandMetricCollector::class,
                'configs' => [
                    'some_key_1' => 'some_value',
                    'some_key_2' => ['some_other_value'],
                ],
            ],
    ```


### Example: Custom OnDemandMetricsCollector

```php
<?php

namespace App\Collectors;

use Illuminate\Support\Facades\DB;
use VMorozov\Prometheus\Collectors\Interfaces\OnDemandMetricCollector;
use CustomGaugeMetric;

class CustomGaugeOnDemandMetricCollector implements OnDemandMetricCollector
{
    public function __construct(
        private CustomGaugeMetric $gauge,
    ) {
    }

    /**
     * @param array{connection: string} $configs
     * @return void
     */
    public function collect(array $configs): void
    {
        $this->gauge->setValue($activeConnections, [
            'connection_name' => $configs['connection'],
        ]);
    }
}
```

## Registering Custom On Demand Metrics Collectors

To register your custom OnDemandMetricsCollector, you need to:

1. Add your collector to the `on_demand_metric_collectors` array in the `laravel-prometheus.php` configuration file


### Configuration Example

```php
// config/laravel-prometheus.php

return [
    // ... other configuration options ...
    'on_demand_metric_collectors' => [
        // ... default collectors here ...
        [
            'class' => App\Collectors\CustomOnDemandMetricCollector::class,
            'configs' => [
                'connections' => ['mysql', 'pgsql'],
            ],
        ],
    ],
];
```

## How On Demand Metrics Collectors Work

When the metrics API endpoint is called, the package will:

1. Instantiate each collector class specified in the configuration
2. Call the `collect` method on each collector, passing the configs from the configuration file
3. The collector will then collect the metrics and update the corresponding metric objects

This allows you to collect metrics that might be expensive to calculate on every request, but still have them available when you need them.

## Best Practices

1. **Use On Demand Metrics Collectors for expensive operations**:
   - Database queries that count records
   - File system operations
   - External API calls

2. **Keep the collect method efficient**:
   - Avoid unnecessary database queries
   - Use caching where appropriate
   - Limit the scope of what you're collecting

3. **Use meaningful configuration options**:
   - Allow users to specify what they want to collect
   - Provide sensible defaults
   - Document the available options

4. **Handle errors gracefully**:
   - Catch exceptions and log them
   - Provide fallback values when operations fail
   - Don't let one collector failure prevent others from running
