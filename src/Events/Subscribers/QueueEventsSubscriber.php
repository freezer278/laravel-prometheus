<?php

namespace VMorozov\Prometheus\Events\Subscribers;

use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Events\JobQueued;
use VMorozov\Prometheus\Collectors\DefaultMetrics\QueuePushedJobsCounterMetricCollector;

class QueueEventsSubscriber
{
    public function __construct(
        private QueuePushedJobsCounterMetricCollector $queuePushedJobsCounterMetricCollector
    ) {
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            JobQueued::class => 'handleJobPushedToQueueEvent',
        ];
    }

    public function handleJobPushedToQueueEvent(JobQueued $event): void
    {
        $this->queuePushedJobsCounterMetricCollector->recordJob($event);
    }
}
