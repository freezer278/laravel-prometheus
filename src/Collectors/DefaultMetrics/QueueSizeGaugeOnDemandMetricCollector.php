<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use VMorozov\Prometheus\Collectors\AbstractGaugeMetricCollector;
use VMorozov\Prometheus\Collectors\Interfaces\OnDemandMetricCollector;
use VMorozov\Prometheus\Metrics\Default\QueueSizeGauge;

class QueueSizeGaugeOnDemandMetricCollector implements OnDemandMetricCollector
{
    public function __construct(
        private QueueSizeGauge $gauge
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

        foreach ($connections as $connection) {
            foreach ($queues as $queue) {
                // todo: replace it with real queue size getting
                $size = random_int(0, 1000);

                $this->gauge->setValue($size, [
                    'connection_name' => $connection,
                    'queue_name' => $queue,
                ]);
            }
        }
    }
}
