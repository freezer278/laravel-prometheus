<?php

namespace VMorozov\Prometheus\Collectors;

use Prometheus\Counter;

abstract class AbstractTotalCounterMetricCollector extends AbstractMetricCollector
{
    public const TYPE_ALL = 'all';

    public const TYPE_SUCCESSFUL = 'successful';

    public const TYPE_FAILED = 'failed';

    public function incrementFailed(): void
    {
        $counter = $this->getCounter();
        $counter->inc([self::TYPE_FAILED]);
        $counter->inc([self::TYPE_ALL]);
    }

    public function incrementSuccessful(): void
    {
        $counter = $this->getCounter();
        $counter->inc([self::TYPE_SUCCESSFUL]);
        $counter->inc([self::TYPE_ALL]);
    }

    private function getCounter(): Counter
    {
        return $this->collectionRegistry->getOrRegisterCounter(
            $this->namespace,
            $this->getMetricName(),
            $this->getHelpText(),
            ['type'],
        );
    }

    abstract protected function getMetricName(): string;

    abstract protected function getHelpText(): string;
}
