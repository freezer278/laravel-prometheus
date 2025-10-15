<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use VMorozov\Prometheus\Metrics\Default\ConsoleCommandDurationHistogramMetric;

class ConsoleCommandDurationHistogramMetricCollector
{
    /**
     * @var array<string,float>
     */
    private array $starts = [];

    public function __construct(
        private ConsoleCommandDurationHistogramMetric $metric,
    ) {
    }

    public function recordCommandStart(CommandStarting $event): void
    {
        $this->starts[$event->command] = microtime(true);
    }

    public function recordCommandEnd(CommandFinished $event): void
    {
        $start = $this->starts[$event->command] ?? null;

        if ($start === null) {
            return; // no start time captured; avoid recording
        }

        unset($this->starts[$event->command]);

        $durationMs = (microtime(true) - $start) * 1000;

        $this->metric->addItem($durationMs, [
            'command' => $event->command,
            'exit_code' => $event->exitCode,
        ]);
    }
}
