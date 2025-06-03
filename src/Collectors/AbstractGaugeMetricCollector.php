<?php

namespace VMorozov\Prometheus\Collectors;

use Prometheus\Gauge;

abstract class AbstractGaugeMetricCollector extends AbstractMetricCollector
{
    public function setValue(float $value, array $labels = []): void
    {
        $gauge = $this->getGauge();
        $gauge->set($value, $labels);
    }

    private function getGauge(): Gauge
    {
        return $this->collectionRegistry->getOrRegisterGauge(
            $this->namespace,
            $this->getMetricName(),
            $this->getHelpText(),
            $this->getLabels(),
        );
    }

    protected function getLabels(): array
    {
        return [];
    }

    abstract protected function getMetricName(): string;

    abstract protected function getHelpText(): string;
}
