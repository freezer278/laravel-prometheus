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

    protected function getLabelNames(): array
    {
        return [];
    }

    abstract protected function getMetricName(): string;

    abstract protected function getHelpText(): string;
}
