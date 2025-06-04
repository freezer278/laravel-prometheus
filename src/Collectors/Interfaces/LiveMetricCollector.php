<?php

namespace VMorozov\Prometheus\Collectors\Interfaces;

/**
 *  This interface is used to mark collectors that should be updated in real time on API route call.
 */
interface LiveMetricCollector extends MetricCollector
{
    public function collect(): void;
}
