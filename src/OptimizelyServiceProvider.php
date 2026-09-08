<?php

namespace JeffersonGoncalves\Optimizely;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OptimizelyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('optimizely')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Optimizely::class, function () {
            $projectId = config('optimizely.project_id');

            return new Optimizely(
                (string) config('optimizely.api_key'),
                (string) config('optimizely.base_url', 'https://api.optimizely.com/v2'),
                $projectId !== null ? (int) $projectId : null,
                (int) config('optimizely.default_per_page', 25),
            );
        });
    }
}
