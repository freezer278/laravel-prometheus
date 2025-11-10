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
            25.0,     // 25ms
            50.0,     // 50ms
            100.0,    // 100ms
            250.0,    // 250ms
            500.0,    // 500ms
            750.0,    // 750ms
            1_000.0,   // 1 second
            2_500.0,   // 2.5 seconds
            5_000.0,   // 5 seconds
            10_000.0,  // 10 seconds
            20_000.0,  // 20 seconds
            40_000.0,  // 40 seconds
            60_000.0,  // 1 minute
            120_000.0, // 2 minutes
            300_000.0, // 5 minutes
            600_000.0, // 10 minutes
            1_800_000.0, // 30 minutes
            3_600_000.0, // 1 hour
            7_200_000.0, // 2 hours
            14_400_000.0, // 4 hours
            21_600_000.0, // 6 hours
            28_800_000.0, // 8 hours
            36_000_000.0, // 10 hours
        ];
    }
}
