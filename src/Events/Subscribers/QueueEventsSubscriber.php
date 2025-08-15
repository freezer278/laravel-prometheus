<?php

namespace VMorozov\Prometheus\Events\Subscribers;

use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobQueued;
use VMorozov\Prometheus\Collectors\DefaultMetrics\QueueProcessedJobsCounterMetricCollector;
use VMorozov\Prometheus\Collectors\DefaultMetrics\QueuePushedJobsCounterMetricCollector;

class QueueEventsSubscriber
{
    public function __construct(
        private QueuePushedJobsCounterMetricCollector $pushedJobsCollector,
        private QueueProcessedJobsCounterMetricCollector $processedJobsCollector
    ) {
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            JobQueued::class => 'handleJobPushedToQueueEvent',
            JobProcessed::class => 'handleJobProcessedEvent',
        ];
    }

    public function handleJobPushedToQueueEvent(JobQueued $event): void
    {
        $this->pushedJobsCollector->recordJob($event);
    }

    public function handleJobProcessedEvent(JobProcessed $event): void
    {
        $this->processedJobsCollector->recordProcessedJob($event);
    }
}
