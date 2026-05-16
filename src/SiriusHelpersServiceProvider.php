<?php

declare(strict_types=1);

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
        AboutCommand::add('Environment', fn (): array => ['SiriusHelpers Version' => '1.2.0']);
    }
}
