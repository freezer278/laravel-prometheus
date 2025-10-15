<?php

namespace VMorozov\Prometheus\Events\Subscribers;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Events\Dispatcher;
use VMorozov\Prometheus\Collectors\DefaultMetrics\ConsoleCommandDurationHistogramMetricCollector;
use VMorozov\Prometheus\PrometheusServiceProvider;

class ConsoleCommandsEventsSubscriber
{
    public const DEFAULT_LARAVEL_COMMAND_PATTERNS_TO_SKIP = [
        'about:',
        'cache:',
        'clear-compiled',
        'completion',
        'config:',
        'db:',
        'down',
        'env:',
        'help',
        'inspire',
        'list',
        'make:',
        'migrate:',
        'optimize:',
        'package:',
        'pail',
        'prometheus:',
        'queue:',
        'route:',
        'sail:',
        'schedule:',
        'schema:',
        'serve',
        'storage:',
        'stub:',
        'test',
        'tinker',
        'up',
        'vendor:',
        'view:',
    ];

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
        if ($this->commandHasToBeSkipped($event->command)) {
            return;
        }

        $this->durationCollector->recordCommandStart($event);
    }

    public function handleCommandFinishedEvent(CommandFinished $event): void
    {
        if ($this->commandHasToBeSkipped($event->command)) {
            return;
        }

        $this->durationCollector->recordCommandEnd($event);
    }

    private function commandHasToBeSkipped(string $name): bool
    {
        if (!config(PrometheusServiceProvider::CONFIG_KEY . '.console_commands_metrics.exclude_default_laravel_commands', true)) {
            return false;
        }

        if (in_array($name, self::DEFAULT_LARAVEL_COMMAND_PATTERNS_TO_SKIP)) {
            return true;
        }

        foreach (self::DEFAULT_LARAVEL_COMMAND_PATTERNS_TO_SKIP as $pattern) {
            if (str_contains($name, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
