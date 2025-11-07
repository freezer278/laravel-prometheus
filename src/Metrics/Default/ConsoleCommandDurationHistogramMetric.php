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
            10_000.0,
            20_000.0,
            30_000.0,
            40_000.0,
            50_000.0,
            60_000.0,
            70_000.0,
            80_000.0,
            90_000.0,
            100_000.0,
            110_000.0,
            120_000.0,
            250_000.0,
            500_000.0,
            750_000.0,
            1_000_000.0,
            1_250_000.0,
            1_500_000.0,
            1_750_000.0,
            2_000_000.0,
            2_250_000.0,
            2_500_000.0,
            2_750_000.0,
            3_000_000.0,
            3_500_000.0,
            4_000_000.0,
            4_500_000.0,
            5_000_000.0,
            6_000_000.0,
            7_000_000.0,
            8_000_000.0,
            9_000_000.0,
            10_000_000.0,
            20_000_000.0,
            30_000_000.0,
            40_000_000.0,
            50_000_000.0,
        ];
    }
}
