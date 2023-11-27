<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Http\Request;
use VMorozov\Prometheus\Collectors\AbstractSummaryMetricCollector;

class RequestDurationSummaryMetricCollector extends AbstractSummaryMetricCollector
{
    protected function getMetricName(): string
    {
        return 'request_duration_summary_milliseconds';
    }

    protected function getHelpText(): string
    {
        return 'requests durations in ms';
    }

    protected function getLabels(): array
    {
        return ['url', 'method', 'host'];
    }

    protected function getQuantiles(): array
    {
        return [0.01, 0.25, 0.5, 0.95, 0.98];
    }

    public function recordRequest(Request $request, float $startTime): void
    {
        $endTime = microtime(true);
        $diffInMilliseconds = ($endTime - $startTime) * 1000;

        $this->addItem($diffInMilliseconds, [
            $request->path(),
            $request->method(),
            $request->server('HOSTNAME'),
        ]);
    }
}
