# Custom Metrics Development and Usage

This document provides information on how to develop and use custom metrics with the Laravel Prometheus package.

## Types of Metrics

The package supports three types of metrics:

1. **Counter** - A cumulative metric that represents a single monotonically increasing counter whose value can only increase or be reset to zero on restart.
2. **Gauge** - A metric that represents a single numerical value that can arbitrarily go up and down.
3. **Histogram** - A metric that samples observations (usually things like request durations or response sizes) and counts them in configurable buckets.

## Creating Custom Metrics

To create a custom metric, you need to extend one of the abstract metric classes:

- `AbstractCounterMetric` - For counter metrics
- `AbstractGaugeMetric` - For gauge metrics
- `AbstractHistogramMetric` - For histogram metrics

### Example: Custom Counter Metric

```php
<?php

namespace App\Metrics;

use VMorozov\Prometheus\Metrics\AbstractCounterMetric;

class ApiRequestsCounterMetric extends AbstractCounterMetric
{
    protected function getMetricName(): string
    {
        return 'api_requests_counter';
    }

    protected function getHelpText(): string
    {
        return 'count of API requests';
    }

    protected function getLabelNames(): array
    {
        return ['endpoint', 'method', 'status'];
    }
}
```

### Example: Custom Gauge Metric

```php
<?php

namespace App\Metrics;

use VMorozov\Prometheus\Metrics\AbstractGaugeMetric;

class ActiveUsersGaugeMetric extends AbstractGaugeMetric
{
    protected function getMetricName(): string
    {
        return 'active_users_gauge';
    }

    protected function getHelpText(): string
    {
        return 'number of currently active users';
    }

    protected function getLabelNames(): array
    {
        return ['user_type'];
    }
}
```

### Example: Custom Histogram Metric

```php
<?php

namespace App\Metrics;

use VMorozov\Prometheus\Metrics\AbstractHistogramMetric;

class DatabaseQueryDurationHistogramMetric extends AbstractHistogramMetric
{
    protected function getMetricName(): string
    {
        return 'database_query_duration_histogram_ms';
    }

    protected function getHelpText(): string
    {
        return 'database query durations in ms';
    }

    protected function getLabelNames(): array
    {
        return ['query_type', 'table'];
    }

    protected function getBuckets(): array
    {
        return [
            1.0,
            5.0,
            10.0,
            25.0,
            50.0,
            100.0,
            250.0,
            500.0,
            1000.0,
            2500.0,
            5000.0,
            10000.0,
        ];
    }
}
```

## Using Custom Metrics

You can inject your metric into your classes and use it:

### Counter Example

```php
<?php

namespace App\Http\Controllers;

use App\Metrics\ApiRequestsCounterMetric;

class ApiController extends Controller
{
    public function __construct(
        private ApiRequestsCounterMetric $apiRequestsCounter,
    ) {
    }

    public function index()
    {
        // Increment the counter
        $this->apiRequestsCounter->increment([
            'endpoint' => 'api/index',
            'method' => 'GET',
            'status' => '200',
        ]);

        // Or increment by a specific value
        $this->apiRequestsCounter->incrementBy(5, [
            'endpoint' => 'api/index',
            'method' => 'GET',
            'status' => '200',
        ]);

        // Rest of your controller logic
    }
}
```

### Gauge Example

```php
<?php

namespace App\Services;

use App\Metrics\ActiveUsersGaugeMetric;

class UserService
{
    public function __construct(
        private ActiveUsersGaugeMetric $activeUsersGauge,
    ) {
    }

    public function updateActiveUsersCount()
    {
        $regularUsersCount = $this->countActiveRegularUsers();
        $adminUsersCount = $this->countActiveAdminUsers();

        // Set the gauge values
        $this->activeUsersGauge->setValue($regularUsersCount, [
            'user_type' => 'regular',
        ]);

        $this->activeUsersGauge->setValue($adminUsersCount, [
            'user_type' => 'admin',
        ]);
    }

    private function countActiveRegularUsers()
    {
        // Your logic to count active regular users
        return 100;
    }

    private function countActiveAdminUsers()
    {
        // Your logic to count active admin users
        return 5;
    }
}
```

### Histogram Example

```php
<?php

namespace App\Services;

use App\Metrics\DatabaseQueryDurationHistogramMetric;

class DatabaseService
{
    public function __construct(
        private DatabaseQueryDurationHistogramMetric $queryDurationHistogram,
    ) {
    }

    public function executeQuery($query, $table, $type)
    {
        $startTime = microtime(true);

        // Execute the query
        $result = $this->performQuery($query);

        $endTime = microtime(true);
        $durationMs = ($endTime - $startTime) * 1000;

        // Record the query duration
        $this->queryDurationHistogram->addItem($durationMs, [
            'query_type' => $type,
            'table' => $table,
        ]);

        return $result;
    }

    private function performQuery($query)
    {
        // Your query execution logic
        return [];
    }
}
```

## Best Practices

1. **Choose the right metric type** for your use case:
   - Use counters for values that only increase (e.g., request counts, error counts)
   - Use gauges for values that can go up and down (e.g., queue sizes, active connections)
   - Use histograms for measuring distributions of values (e.g., request durations, response sizes)

2. **Use meaningful labels** but avoid high cardinality (too many unique label combinations):
   - Good: `method` (GET, POST, etc.), `status` (200, 404, etc.)
   - Bad: `user_id`, `request_id` (these can have thousands or millions of unique values)

3. **Use consistent naming conventions** for your metrics:
   - Use snake_case for metric names
   - Add a suffix indicating the metric type (_counter, _gauge, _histogram)
   - Add a unit suffix where applicable (_seconds, _bytes, _total)

4. **Provide clear help text** that explains what the metric measures and its units
