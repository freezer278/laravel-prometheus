<?php

namespace VMorozov\Prometheus\Metrics;

use Prometheus\Gauge;

abstract class AbstractGaugeMetric extends AbstractMetric
{
    public function setValue(float $value, array $labels = []): void
    {
        $gauge = $this->getGauge();
        $gauge->set($value, $labels);
    }

    private function getGauge(): Gauge
    {
        return $this->collectorRegistry->getOrRegisterGauge(
            $this->namespace,
            $this->getMetricName(),
            $this->getHelpText(),
            $this->getLabelNames(),
        );
    }
}
