<?php

namespace Robiokidenis\FilamentMultiTenancy;

use Robiokidenis\FilamentMultiTenancy\Commands\FilamentMultiTenancyCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMultiTenancyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-multi-tenancy')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_filament_multi_tenancy_table')
            ->hasCommand(FilamentMultiTenancyCommand::class);
    }
}
