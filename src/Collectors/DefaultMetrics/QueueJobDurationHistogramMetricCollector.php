<?php

namespace VMorozov\Prometheus\Collectors\DefaultMetrics;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Contracts\Queue\Job as QueueJobContract;
use VMorozov\Prometheus\Metrics\Default\QueueJobDurationHistogramMetric;
use VMorozov\Prometheus\Metrics\Default\QueueProcessedJobsCounterMetric;

class QueueJobDurationHistogramMetricCollector
{
    /**
     * @var array<string,float>
     */
    private array $starts = [];

    public function __construct(
        private QueueJobDurationHistogramMetric $histogram,
    ) {
    }

    public function recordJobProcessingStart(JobProcessing $event): void
    {
        $identifier = $this->getJobIdentifier($event->job);
        $this->recordProcessingStart($identifier);
    }

    public function recordJobProcessedSuccessfully(JobProcessed $event): void
    {
        $this->recordAndCleanup($event->connectionName, $event->job, QueueProcessedJobsCounterMetric::STATUS_SUCCESS);
    }

    public function recordJobFailed(JobFailed $event): void
    {
        $this->recordAndCleanup($event->connectionName, $event->job, QueueProcessedJobsCounterMetric::STATUS_FAILED);
    }

    private function recordAndCleanup(string $connectionName, QueueJobContract $job, string $status): void
    {
        $identifier = $this->getJobIdentifier($job);
        $start = $this->getAndClearProcessingStartTime($identifier);

        if ($start === null) {
            return; // no start time captured; avoid recording
        }

        $durationMs = (microtime(true) - $start) * 1000;

        $this->histogram->addItem($durationMs, [
            'connection_name' => $connectionName,
            'queue_name' => $job->getQueue(),
            'queue_job_name' => $job->resolveName(),
            'status' => $status,
        ]);
    }

    private function recordProcessingStart(string $jobId): void
    {
        $this->starts[$jobId] = microtime(true);
    }

    private function getAndClearProcessingStartTime(string $jobId): ?float
    {
        $start = $this->starts[$jobId] ?? null;
        unset($this->starts[$jobId]);

        return $start;
    }

    private function getJobIdentifier(QueueJobContract $job): string
    {
        $uuid = method_exists($job, 'uuid') ? $job->uuid() : null;
        if (!empty($uuid)) {
            return (string) $uuid;
        }

        $id = $job->getJobId();
        if (!empty($id)) {
            return (string) $id;
        }

        return spl_object_hash($job);
    }
}
