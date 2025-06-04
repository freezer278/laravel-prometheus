<?php

namespace VMorozov\Prometheus\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use VMorozov\Prometheus\Collectors\Interfaces\OnDemandMetricCollector;
use VMorozov\Prometheus\PrometheusServiceProvider;

class MetricsController
{
    public function __construct(
        private CollectorRegistry $collectorRegistry,
        private RenderTextFormat $textRenderer,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->callLiveMetricsCollectors();

        $result = $this->textRenderer->render($this->collectorRegistry->getMetricFamilySamples());

        return new Response($result, 200, ['Content-type' => RenderTextFormat::MIME_TYPE]);
    }

    private function callLiveMetricsCollectors(): void
    {
        $onDemandMetricCollectors = config(PrometheusServiceProvider::CONFIG_KEY . '.on_demand_metric_collectors', []);

        foreach ($onDemandMetricCollectors as $onDemandCollector) {
            $class = $onDemandCollector['class'];
            $configs = $onDemandCollector['configs'];

            $liveMetricsCollector = app()->make($class);

            if (!$liveMetricsCollector instanceof OnDemandMetricCollector) {
                continue;
            }

            $liveMetricsCollector->collect($configs);
        }
    }
}
