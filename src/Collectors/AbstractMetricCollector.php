<?php

namespace VMorozov\Prometheus\Collectors;

use Prometheus\CollectorRegistry;
use VMorozov\Prometheus\Collectors\Interfaces\MetricCollector;

abstract class AbstractMetricCollector implements MetricCollector
{
    protected CollectorRegistry $collectionRegistry;
    protected string $namespace;

    public function __construct(CollectorRegistry $collectorRegistry)
    {
        $this->collectionRegistry = $collectorRegistry;
        $this->namespace = config('laravel-prometheus.namespace');
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }
}
