<?php

namespace VMorozov\Prometheus\Metrics\Default;

use VMorozov\Prometheus\Metrics\AbstractHistogramMetric;

class ConsoleCommandDurationHistogramMetric extends AbstractHistogramMetric
{
    protected function getMetricName(): string
    {
        return 'console_command_duration_histogram_ms';
    }

    protected function getHelpText(): string
    {
        return 'console command durations in ms';
    }

    protected function getLabelNames(): array
    {
        return ['command', 'exit_code'];
    }

    protected function getBuckets(): array
    {
        return [
            10.0,
            50.0,
            100.0,
            150.0,
            200.0,
            250.0,
            300.0,
            400.0,
            500.0,
            750.0,
            1000.0,
            2500.0,
            5000.0,
            7500.0,
            10000.0,
            30000.0,
            60000.0,
        ];
    }
}
