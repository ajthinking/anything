<?php

namespace Anything\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Anything\AnythingServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            AnythingServiceProvider::class,
        ];
    }
}
