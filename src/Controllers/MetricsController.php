<?php

namespace VMorozov\Prometheus\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

class MetricsController
{
    public function __construct(
        private CollectorRegistry $collectorRegistry,
        private RenderTextFormat $textRenderer,
    ) {
    }

    public function __invoke(Request $request)
    {
        $result = $this->textRenderer->render($this->collectorRegistry->getMetricFamilySamples());

        return new Response($result, 200, ['Content-type' => RenderTextFormat::MIME_TYPE]);
    }
}
