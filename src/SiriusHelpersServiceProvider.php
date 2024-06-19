<?php

namespace SiriusProgram\SiriusHelpers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Foundation\Console\AboutCommand;

class SiriusHelpersServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('sirius-helpers')
            ->hasConfigFile();
    }

    public function bootingPackage(): void
    {
        AboutCommand::add('Environment', fn () => ['SiriusHelpers Version' => '1.0.1']);
    }
}
