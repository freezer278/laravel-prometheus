<?php

namespace VMorozov\Prometheus;

use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\APC;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VMorozov\Prometheus\Collectors\DefaultMetrics\QueueJobDurationHistogramMetricCollector;
use VMorozov\Prometheus\Controllers\MetricsController;
use VMorozov\Prometheus\Events\Subscribers\QueueEventsSubscriber;
use VMorozov\Prometheus\Middleware\CollectRequestDurationMetric;

class PrometheusServiceProvider extends PackageServiceProvider
{
    public const CONFIG_KEY = 'laravel-prometheus';

    public const STORAGE_TYPE_REDIS = 'redis';

    public const STORAGE_TYPE_IN_MEMORY = 'in_memory';

    public const STORAGE_TYPE_APC = 'apcu';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::CONFIG_KEY)
            ->hasConfigFile(self::CONFIG_KEY);
    }

    public function packageRegistered(): void
    {
        $this->initCollectorRegistry();

        $this->initRoutes();

        /** @var Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $middleware = $this->app->make(CollectRequestDurationMetric::class);
        $this->app->singleton(CollectRequestDurationMetric::class, function () use ($middleware) {
            return $middleware;
        });
        $kernel->prependMiddleware(CollectRequestDurationMetric::class);
    }

    public function packageBooted()
    {
        $this->initQueueJobsMetricsCollection();
    }

    private function initRoutes(): void
    {
        Route::get(config(self::CONFIG_KEY . '.route_url', 'metrics'), MetricsController::class)
            ->middleware(config(self::CONFIG_KEY . '.route_middleware', []))
            ->name('metrics');
    }

    private function initCollectorRegistry(): void
    {
        $type = config(self::CONFIG_KEY . '.storage_type');
        switch ($type) {
            case self::STORAGE_TYPE_REDIS:
                $storage = $this->initRedisStorage();

                break;
            case self::STORAGE_TYPE_IN_MEMORY:
                $storage = new InMemory();

                break;
            case self::STORAGE_TYPE_APC:
                $storage = new APC();

                break;
            default:
                throw new InvalidArgumentException(
                    'Wrong value in "laravel-prometheus.storage_type" config: ' . $type,
                );
        }

        $registry = new CollectorRegistry($storage, false);
        $this->app->instance(CollectorRegistry::class, $registry);
    }

    private function initRedisStorage(): Redis
    {
        $connectionName = config(self::CONFIG_KEY . '.redis_connection', 'default');
        $connection = config('database.redis.' . $connectionName);

        if (!$connection) {
            throw new InvalidArgumentException(
                "Invalid redis connection name in prometheus config: $connectionName",
            );
        }

        return new Redis([
            'host' => $connection['host'],
            'port' => $connection['port'],
            'password' => $connection['password'],
            'timeout' => 0.1, // in seconds
            'read_timeout' => '10', // in seconds
            'persistent_connections' => false,
            'database' => (int) $connection['database'],
        ]);
    }

    private function initQueueJobsMetricsCollection(): void
    {
        $this->app->singleton(QueueJobDurationHistogramMetricCollector::class);

        Event::subscribe(QueueEventsSubscriber::class);
    }
}
