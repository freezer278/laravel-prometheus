<?php

namespace VMorozov\Prometheus\Metrics;

use Prometheus\Counter;

abstract class AbstractCounterMetric extends AbstractMetric
{
    public function increment(array $labels = []): void
    {
        $counter = $this->getCounter();
        $counter->inc($this->extractLabelsFromAssocArray($labels));
    }

    public function incrementBy(float $value, array $labels = []): void
    {
        $counter = $this->getCounter();
        $counter->incBy($value, $this->extractLabelsFromAssocArray($labels));
    }

    private function getCounter(): Counter
    {
        return $this->collectorRegistry->getOrRegisterCounter(
            $this->namespace,
            $this->getMetricName(),
            $this->getHelpText(),
            $this->getLabelNames(),
        );
    }
}
