<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Support\Facades\Queue;
use VMorozov\Prometheus\Collectors\Interfaces\OnDemandMetricCollector;
use VMorozov\Prometheus\Metrics\Default\QueueSizeGaugeMetric;

class QueueSizeGaugeOnDemandMetricCollector implements OnDemandMetricCollector
{
    public function __construct(
        private QueueSizeGaugeMetric $gauge,
    ) {
    }

    /**
     * @param array{connections?: string[], queues?: string[]} $configs
     * @return void
     * @throws \Random\RandomException
     */
    public function collect(array $configs): void
    {
        $connections = $configs['connections'] ?? ['redis'];
        $queues = $configs['queues'] ?? ['default'];

        foreach ($connections as $connectionName) {
            $connection = Queue::connection($connectionName);

            foreach ($queues as $queue) {
                $size = $connection->size($queue);
                $this->gauge->setValue($size, [
                    'connection_name' => $connectionName,
                    'queue_name' => $queue,
                ]);
            }
        }
    }
}
