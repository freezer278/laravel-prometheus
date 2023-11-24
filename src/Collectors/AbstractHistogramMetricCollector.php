<?php

namespace VMorozov\Prometheus\Collectors;

use Prometheus\Histogram;

abstract class AbstractHistogramMetricCollector extends AbstractMetricCollector
{
    public const MAX_AGE_MINUTES = 60;

    public function addItem(float $item, array $labels = []): void
    {
        $summary = $this->getHistogram();
        $summary->observe($item, $labels);
    }

    private function getHistogram(): Histogram
    {
        return $this->collectionRegistry->getOrRegisterHistogram(
            $this->namespace,
            $this->getMetricName(),
            $this->getHelpText(),
            $this->getLabels(),
            $this->getBuckets(),
        );
    }

    abstract protected function getMetricName(): string;

    abstract protected function getHelpText(): string;

    protected function getLabels(): array
    {
        return [];
    }

    protected function getBuckets(): array
    {
        return Histogram::getDefaultBuckets();
    }
}
