<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Queue\Events\JobQueued;
use VMorozov\Prometheus\Metrics\Default\QueuePushedJobsCounterMetric;

class QueuePushedJobsCounterMetricCollector
{
    public function __construct(
        private QueuePushedJobsCounterMetric $metric,
    ) {
    }

    public function recordJob(JobQueued $event): void
    {
        $this->metric->increment([
            'connection_name' => $event->connectionName,
            'queue_name' => $event->queue,
            'queue_job_name' => get_class($event->job),
        ]);
    }
}
