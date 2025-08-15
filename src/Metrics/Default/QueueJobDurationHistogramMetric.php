<?php

namespace VMorozov\Prometheus\Metrics\Default;

use VMorozov\Prometheus\Metrics\AbstractHistogramMetric;

class QueueJobDurationHistogramMetric extends AbstractHistogramMetric
{
    protected function getMetricName(): string
    {
        return 'queue_job_processing_duration_histogram_ms';
    }

    protected function getHelpText(): string
    {
        return 'queue job processing durations in ms';
    }

    protected function getLabelNames(): array
    {
        return ['connection_name', 'queue_name', 'queue_job_name', 'status'];
    }

    protected function getBuckets(): array
    {
        return [
            5.0,
            7.0,
            10.0,
            15.0,
            20.0,
            25.0,
            30.0,
            35.0,
            40.0,
            45.0,
            50.0,
            60.0,
            70.0,
            80.0,
            90.0,
            100.0,
            150.0,
            200.0,
            250.0,
            300.0,
            350.0,
            400.0,
            450.0,
            500.0,
            600.0,
            700.0,
            750.0,
            1000.0,
            2000.0,
            3000.0,
            4000.0,
            5000.0,
            6000.0,
            7000.0,
            8000.0,
            9000.0,
            10000.0,
            30000.0,
            60000.0,
        ];
    }
}
