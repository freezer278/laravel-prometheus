<?php

namespace VMorozov\Prometheus;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PrometheusServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-prometheus')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        // todo: add here init of CollectorRegistry and it's storage
    }
}
