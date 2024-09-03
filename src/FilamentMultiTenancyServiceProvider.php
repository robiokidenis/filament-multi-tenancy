<?php

namespace Robiokidenis\FilamentMultiTenancy;

use Illuminate\Database\Schema\Blueprint;
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
            // ->hasRoute('socialite')
            ->hasMigration('create_filament_multi_tenancy_table');
        // ->hasCommand(FilamentMultiTenancyCommand::class)

    }

    public function boot()
    {
        parent::boot();

        Blueprint::macro('userTracking', function () {
            $this->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $this->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $this->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }
}
