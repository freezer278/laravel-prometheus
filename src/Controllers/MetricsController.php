<?php

namespace VMorozov\Prometheus\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use VMorozov\Prometheus\Collectors\Interfaces\LiveMetricCollector;
use VMorozov\Prometheus\PrometheusServiceProvider;

class MetricsController
{
    public function __construct(
        private CollectorRegistry $collectorRegistry,
        private RenderTextFormat $textRenderer,
    ) {}

    public function __invoke(Request $request): Response
    {
        $this->callLiveMetricsCollectors();

        $result = $this->textRenderer->render($this->collectorRegistry->getMetricFamilySamples());

        return new Response($result, 200, ['Content-type' => RenderTextFormat::MIME_TYPE]);
    }

    private function callLiveMetricsCollectors(): void
    {
        $liveMetricsCollectorClasses = config(PrometheusServiceProvider::CONFIG_KEY . '.live_metrics_collectors');

        foreach ($liveMetricsCollectorClasses as $liveMetricsCollectorClass) {
            $liveMetricsCollector = app()->make($liveMetricsCollectorClass);

            if (! $liveMetricsCollector instanceof LiveMetricCollector) {
                continue;
            }

            $liveMetricsCollector->collect();
        }
    }
}
