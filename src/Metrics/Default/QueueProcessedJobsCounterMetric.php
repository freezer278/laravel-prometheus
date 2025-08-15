
<?php

namespace VMorozov\Prometheus\Metrics\Default;

use VMorozov\Prometheus\Metrics\AbstractCounterMetric;
use VMorozov\Prometheus\Metrics\AbstractGaugeMetric;

class QueueProcessedJobsCounterMetric extends AbstractCounterMetric
{
    protected function getMetricName(): string
    {
        return 'queue_processed_jobs_counter';
    }

    protected function getHelpText(): string
    {
        return 'total number of jobs processed in the queue';
    }

    protected function getLabelNames(): array
    {
        return ['connection_name', 'queue_name', 'queue_job_name', 'status'];
    }
}
