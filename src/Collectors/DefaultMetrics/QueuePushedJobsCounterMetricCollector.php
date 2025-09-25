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
            'queue_name' => $this->resolveQueueName($event),
            'queue_job_name' => get_class($event->job),
        ]);
    }

    private function resolveQueueName(JobQueued $event): string
    {
        if (isset($event->queue)) {
            return $event->queue;
        }

        if (method_exists($event->job, 'queue')) {
            return $event->job->queue();
        }

        return $event->job->queue ?? config('queue.connections.redis.queue') ?? 'default';
    }
}
