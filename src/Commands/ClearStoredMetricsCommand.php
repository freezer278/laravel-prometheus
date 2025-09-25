<?php

namespace VMorozov\Prometheus\Commands;

use Illuminate\Console\Command;
use Prometheus\CollectorRegistry;

class ClearStoredMetricsCommand extends Command
{
    protected $signature = 'prometheus:clear_stored_metrics';

    protected $description = 'Clears stored metrics from the storage (redis)';

    public function handle(CollectorRegistry $metricsCollectorRegistry): void
    {
        $metricsCollectorRegistry->wipeStorage();
        $this->info('Metrics cleared from storage');
    }
}
