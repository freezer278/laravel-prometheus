<?php

namespace VMorozov\Prometheus\Collectors\Interfaces;

/**
 *  This interface is used to mark collectors that should be updated only on metrics API route call.
 */
interface OnDemandMetricCollector
{
    /**
     * Run the collector logic.
     * @param array $configs - configs from the config file to run it properly.
     * @return void
     */
    public function collect(array $configs): void;
}
