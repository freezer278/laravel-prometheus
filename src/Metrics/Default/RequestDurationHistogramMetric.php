<?php

namespace VMorozov\Prometheus\Metrics\Default;

use VMorozov\Prometheus\Metrics\AbstractHistogramMetric;

class RequestDurationHistogramMetric extends AbstractHistogramMetric
{
    protected function getMetricName(): string
    {
        return 'request_duration_histogram_ms';
    }

    protected function getHelpText(): string
    {
        return 'requests durations in ms';
    }

    protected function getLabelNames(): array
    {
        return ['url', 'method', 'status_code'];
    }

    protected function getBuckets(): array
    {
        return [
            25.0,     // 25ms
            50.0,     // 50ms
            100.0,    // 100ms
            200.0,    // 200ms
            300.0,    // 300ms
            400.0,    // 400ms
            500.0,    // 500ms
            750.0,    // 750ms
            1_000.0,  // 1 second
            2_500.0,  // 2.5 seconds
            5_000.0,  // 5 seconds
            10_000.0, // 10 seconds
            30_000.0, // 30 seconds
            60_000.0, // 30 seconds
        ];
    }
}
