<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Http\Request;
use VMorozov\Prometheus\Metrics\Default\RequestDurationHistogramMetric;

class RequestDurationHistogramMetricCollector
{
    public function __construct(
        private RequestDurationHistogramMetric $requestDurationHistogram,
    ) {
    }

    public function recordRequest(Request $request, float $startTime): void
    {
        $endTime = microtime(true);
        $diffInMilliseconds = ($endTime - $startTime) * 1000;

        $this->requestDurationHistogram->addItem($diffInMilliseconds, [
            'url' => $request->path(),
            'method' => $request->method(),
        ]);
    }
}
