<?php

declare(strict_types=1);

namespace SiriusProgram\SiriusHelpers\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use SiriusProgram\SiriusHelpers\SiriusHelpersServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            SiriusHelpersServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // config()->set('database.default', 'testing');
    }
}
