<?php

namespace VMorozov\Prometheus;

use Illuminate\Support\Facades\Route;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\APC;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VMorozov\Prometheus\Controllers\MetricsController;

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

        Route::get(config(self::CONFIG_KEY . '.route_url'), MetricsController::class)
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
                throw new \InvalidArgumentException(
                    'Wrong value in "laravel-prometheus.storage_type" config: ' . $type
                );
        }

        $registry = new CollectorRegistry($storage);
        $this->app->instance(CollectorRegistry::class, $registry);
    }

    private function initRedisStorage(): Redis
    {
        $connectionName = config(self::CONFIG_KEY . '.redis_connection', 'default');
        $connection = config('database.redis.' . $connectionName);

        if (!$connection) {
            throw new \InvalidArgumentException(
                "Invalid redis connection name in prometheus config: $connectionName"
            );
        }

        return new Redis([
            'host' => $connection['host'],
            'port' => $connection['port'],
            'password' => $connection['password'],
            'timeout' => 0.1, // in seconds
            'read_timeout' => '10', // in seconds
            'persistent_connections' => false,
            'database' => (int)$connection['database'],
        ]);
    }
}
