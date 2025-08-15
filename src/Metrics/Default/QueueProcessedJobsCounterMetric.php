<?php

namespace VMorozov\Prometheus\Metrics\Default;

use VMorozov\Prometheus\Metrics\AbstractCounterMetric;

class QueueProcessedJobsCounterMetric extends AbstractCounterMetric
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

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
