<?php

namespace VMorozov\Prometheus\Metrics\Default;

use VMorozov\Prometheus\Metrics\AbstractCounterMetric;
use VMorozov\Prometheus\Metrics\AbstractGaugeMetric;

class QueuePushedJobsCounterMetric extends AbstractCounterMetric
{
    protected function getMetricName(): string
    {
        return 'queue_pushed_jobs_counter';
    }

    protected function getHelpText(): string
    {
        return 'total number of jobs pushed to the queue';
    }

    protected function getLabelNames(): array
    {
        return ['connection_name', 'queue_name', 'queue_job_name'];
    }
}
