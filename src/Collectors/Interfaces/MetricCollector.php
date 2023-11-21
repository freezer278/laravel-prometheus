<?php

namespace VMorozov\Prometheus\Collectors\Interfaces;

interface MetricCollector
{
    public function setNamespace(string $namespace): void;
}
