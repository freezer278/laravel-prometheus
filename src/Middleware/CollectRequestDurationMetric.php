<?php

namespace VMorozov\Prometheus\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use VMorozov\Prometheus\Collectors\DefaultMetrics\RequestDurationHistogramMetricCollector;
use VMorozov\Prometheus\PrometheusServiceProvider;

class CollectRequestDurationMetric
{
    public function __construct(
        private RequestDurationHistogramMetricCollector $histogramMetricCollector,
    ) {
    }

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        if ($request->path() === config(PrometheusServiceProvider::CONFIG_KEY . '.route_url')) {
            return;
        }

        if (!config(PrometheusServiceProvider::CONFIG_KEY . '.route_url', true)) {
            return;
        }

        if (defined('LARAVEL_START')) {
            $start = LARAVEL_START;
        } elseif (defined('APP_START')) {
            $start = APP_START;
        } else {
            return;
        }

        $status = $response->getStatusCode() ?? 200;

        $this->histogramMetricCollector->recordRequest($request, $start, $status);
    }
}
