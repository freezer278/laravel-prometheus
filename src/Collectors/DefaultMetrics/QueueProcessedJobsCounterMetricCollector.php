<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobQueued;
use VMorozov\Prometheus\Metrics\Default\QueueProcessedJobsCounterMetric;
use VMorozov\Prometheus\Metrics\Default\QueuePushedJobsCounterMetric;

class QueueProcessedJobsCounterMetricCollector
{
    public function __construct(
        private QueueProcessedJobsCounterMetric $metric,
    ) {
    }

    public function recordProcessedJob(JobProcessed $event): void
    {
        $this->metric->increment([
            'connection_name' => $event->connectionName,
            'queue_name' => $event->job->getQueue(),
            'queue_job_name' => $event->job->resolveName(),
            'status' => QueueProcessedJobsCounterMetric::STATUS_SUCCESS,
        ]);
    }

    public function recordFailedJob(JobFailed $event): void
    {
        $this->metric->increment([
            'connection_name' => $event->connectionName,
            'queue_name' => $event->job->getQueue(),
            'queue_job_name' => $event->job->resolveName(),
            'status' => QueueProcessedJobsCounterMetric::STATUS_FAILED,
        ]);
    }
}
