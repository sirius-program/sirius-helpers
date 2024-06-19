<?php

namespace SiriusProgram\SiriusHelpers;

use Illuminate\Foundation\Console\AboutCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
