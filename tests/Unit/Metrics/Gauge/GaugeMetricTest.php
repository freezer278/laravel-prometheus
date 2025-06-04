<?php

namespace VMorozov\Prometheus\Tests\Unit\Metrics\Gauge;

use Mockery;
use Prometheus\CollectorRegistry;
use Prometheus\Gauge;
use VMorozov\Prometheus\PrometheusServiceProvider;
use VMorozov\Prometheus\Tests\Unit\UnitTestCase;

class GaugeMetricTest extends UnitTestCase
{
    public const NAMESPACE = 'test_namespace';

    protected function setUp(): void
    {
        parent::setUp();

        config()->set(PrometheusServiceProvider::CONFIG_KEY . '.namespace', self::NAMESPACE);
    }

    public function testShouldSaveNewMetricValueWithLabels(): void
    {
        $expectedValue = random_int(0, 100000);
        $expectedLabels = ['value_1', 'value_2'];

        $metric = $this->getMetricWithAllMocks($expectedValue, $expectedLabels);

        $metric->setValue($expectedValue, $expectedLabels);
    }

    public function testShouldSaveNewMetricValueWithLabelsAsAssocArray(): void
    {
        $expectedValue = random_int(0, 100000);
        $expectedLabels = ['value_1', 'value_2'];
        $realPassedLabels = [
            'label_2' => $expectedLabels[1],
            'label_1' => $expectedLabels[0],
        ];

        $metric = $this->getMetricWithAllMocks($expectedValue, $expectedLabels);

        $metric->setValue($expectedValue, $realPassedLabels);
    }

    /**
     * @param int $expectedValue
     * @param array $expectedLabels
     * @return TestGaugeMetric
     */
    public function getMetricWithAllMocks(int $expectedValue, array $expectedLabels): TestGaugeMetric
    {
        $prometheusGaugeMock = Mockery::mock(Gauge::class);
        $prometheusGaugeMock->shouldReceive('set')
            ->with($expectedValue, $expectedLabels)
            ->once();

        $collectorRegistryMock = Mockery::mock(CollectorRegistry::class);
        $metric = new TestGaugeMetric($collectorRegistryMock);
        $metric->expectedLabelNames = ['label_1', 'label_2'];

        $collectorRegistryMock
            ->shouldReceive('getOrRegisterGauge')
            ->with(
                self::NAMESPACE,
                $metric->expectedName,
                $metric->expectedHelp,
                $metric->expectedLabelNames,
            )
            ->andReturn($prometheusGaugeMock);

        return $metric;
    }
}
