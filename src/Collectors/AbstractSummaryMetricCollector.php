<?php

namespace VMorozov\Prometheus\Collectors;

use Prometheus\Summary;

abstract class AbstractSummaryMetricCollector extends AbstractMetricCollector
{
    public const MAX_AGE_MINUTES = 60;

    public function addItem(float $item, array $labels = []): void
    {
        $summary = $this->getSummary();
        $summary->observe($item, $labels);
    }

    private function getSummary(): Summary
    {
        return $this->collectionRegistry->getOrRegisterSummary(
            $this->namespace,
            $this->getMetricName(),
            $this->getHelpText(),
            $this->getLabels(),
            static::MAX_AGE_MINUTES * 60,
            $this->getQuantiles(),
        );
    }

    abstract protected function getMetricName(): string;

    abstract protected function getHelpText(): string;

    protected function getLabels(): array
    {
        return ['type'];
    }

    protected function getQuantiles(): array
    {
        return [0.01, 0.25, 0.5, 0.95, 0.98];
    }
}
