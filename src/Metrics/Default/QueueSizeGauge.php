<?php

namespace VMorozov\Prometheus\Metrics\Default;

use VMorozov\Prometheus\Metrics\AbstractGaugeMetric;

class QueueSizeGauge extends AbstractGaugeMetric
{
    protected function getMetricName(): string
    {
        return 'queue_size_gauge';
    }

    protected function getHelpText(): string
    {
        return 'current queue size';
    }

    protected function getLabelNames(): array
    {
        return ['connection_name', 'queue_name'];
    }
}
