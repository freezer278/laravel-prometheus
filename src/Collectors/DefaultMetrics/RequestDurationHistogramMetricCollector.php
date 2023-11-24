<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Http\Request;
use VMorozov\Prometheus\Collectors\AbstractHistogramMetricCollector;

class RequestDurationHistogramMetricCollector extends AbstractHistogramMetricCollector
{
    protected function getMetricName(): string
    {
        return 'request_duration_histogram_milliseconds';
    }

    protected function getHelpText(): string
    {
        return 'requests durations in ms';
    }

    protected function getLabels(): array
    {
        return ['url', 'method', 'host'];
    }

    protected function getBuckets(): array
    {
        return [
            10.0,
            50.0,
            100.0,
            150.0,
            160.0,
            170.0,
            180.0,
            190.0,
            200.0,
            250.0,
            300.0,
            400.0,
            500.0,
            750.0,
            1000.0,
            10000.0,
        ];
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
