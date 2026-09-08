<?php

namespace JeffersonGoncalves\Optimizely\Tests;

use JeffersonGoncalves\Optimizely\OptimizelyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            OptimizelyServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('optimizely.api_key', 'test-api-key');
        $app['config']->set('optimizely.project_id', 1234);
    }
}
