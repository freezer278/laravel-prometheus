<?php

namespace VMorozov\Prometheus\Metrics;

use Prometheus\Histogram;

abstract class AbstractHistogramMetric extends AbstractMetric
{
    public function addItem(float $item, array $labels = []): void
    {
        $summary = $this->getHistogram();
        $summary->observe($item, $this->extractLabelsFromAssocArray($labels));
    }

    private function getHistogram(): Histogram
    {
        return $this->collectorRegistry->getOrRegisterHistogram(
            $this->namespace,
            $this->getMetricName(),
            $this->getHelpText(),
            $this->getLabelNames(),
            $this->getBuckets(),
        );
    }

    protected function getBuckets(): array
    {
        return Histogram::getDefaultBuckets();
    }
}
