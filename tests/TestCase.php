<?php

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

    public function getEnvironmentSetUp($app)
    {
        // config()->set('database.default', 'testing');
    }
}
