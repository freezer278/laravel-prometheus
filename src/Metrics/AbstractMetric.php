<?php

namespace VMorozov\Prometheus\Metrics;

use Prometheus\CollectorRegistry;

abstract class AbstractMetric
{
    protected string $namespace;

    public function __construct(
        protected CollectorRegistry $collectorRegistry
    ) {
        $this->namespace = config('laravel-prometheus.namespace');
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    protected function extractLabelsFromAssocArray(array $labels): array
    {
        if (array_is_list($labels)) {
            return $labels;
        }

        $labelNames = $this->getLabelNames();
        $result = [];

        foreach ($labelNames as $name) {
            if (isset($labels[$name])) {
                $result[] = $labels[$name];
            }
        }

        return $result;
    }

    protected function getLabelNames(): array
    {
        return [];
    }

    abstract protected function getMetricName(): string;

    abstract protected function getHelpText(): string;
}
