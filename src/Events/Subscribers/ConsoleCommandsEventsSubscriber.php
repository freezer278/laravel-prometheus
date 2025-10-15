<?php

namespace VMorozov\Prometheus\Events\Subscribers;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Events\Dispatcher;
use VMorozov\Prometheus\Collectors\DefaultMetrics\ConsoleCommandDurationHistogramMetricCollector;

class ConsoleCommandsEventsSubscriber
{
    public function __construct(
        private ConsoleCommandDurationHistogramMetricCollector $durationCollector,
    ) {
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            CommandStarting::class => 'handleCommandStartingEvent',
            CommandFinished::class => 'handleCommandFinishedEvent',
        ];
    }

    public function handleCommandStartingEvent(CommandStarting $event): void
    {
        $this->durationCollector->recordCommandStart($event);
    }

    public function handleCommandFinishedEvent(CommandFinished $event): void
    {
        $this->durationCollector->recordCommandEnd($event);
    }

}
