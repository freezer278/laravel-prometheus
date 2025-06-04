<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use VMorozov\Prometheus\Collectors\AbstractGaugeMetricCollector;
use VMorozov\Prometheus\Collectors\Interfaces\LiveMetricCollector;

class QueueSizeGaugeLiveMetricCollector extends AbstractGaugeMetricCollector implements LiveMetricCollector
{
    protected function getMetricName(): string
    {
        return 'queue_size_gauge';
    }

    protected function getHelpText(): string
    {
        return 'current queue size';
    }

    protected function getLabels(): array
    {
        return ['queue_name', 'connection_name'];
    }

    public function collect(): void
    {
        // todo: get all current queues


        // todo: iterate them and get sizes

        // todo: replace it with real queue size getting
        $size = random_int(0, 1000);

        $this->setValue($size, [
            $event->queue ?? 'default',
            $event->connectionName,
        ]);
    }
}
