<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Http\Request;
use VMorozov\Prometheus\Metrics\Default\QueuePushedJobsCounterMetric;
use VMorozov\Prometheus\Metrics\Default\RequestDurationHistogramMetric;
use Illuminate\Queue\Events\JobProcessing;

class QueuePushedJobsCounterMetricCollector
{
    public function __construct(
        private QueuePushedJobsCounterMetric $metric,
    ) {
    }

    public function recordJob(JobProcessing $event): void
    {
        $this->metric->increment([
            'connection_name' => $event->connectionName,
            'queue_name' => $event->job->getQueue(),
            'queue_job_name' => $event->job->resolveName(),
        ]);
    }
}
