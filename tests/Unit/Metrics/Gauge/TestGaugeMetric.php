<?php

namespace VMorozov\Prometheus\Tests\Unit\Metrics\Gauge;

use Prometheus\CollectorRegistry;
use VMorozov\Prometheus\Metrics\AbstractGaugeMetric;

class TestGaugeMetric extends AbstractGaugeMetric
{
    public string $expectedName = 'test_gauge';
    public string $expectedHelp = 'test gauge help text';
    public array $expectedLabelNames = [];

    public function __construct(
        CollectorRegistry $collectorRegistry,
    ) {
        parent::__construct($collectorRegistry);
    }

    protected function getMetricName(): string
    {
        return $this->expectedName;
    }

    protected function getHelpText(): string
    {
        return $this->expectedHelp;
    }

    protected function getLabelNames(): array
    {
        return $this->expectedLabelNames;
    }
}
