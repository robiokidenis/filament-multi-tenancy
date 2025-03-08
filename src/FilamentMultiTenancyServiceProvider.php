<?php

namespace Robiokidenis\FilamentMultiTenancy;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMultiTenancyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-multi-tenancy')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_filament_multi_tenancy_table');
    }

    public function boot()
    {
        parent::boot();

        Blueprint::macro('userTracking', function () {
            $this->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $this->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $this->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
        });

        Blueprint::macro('hasTenant', function () {
            $this->foreignId(config('filament-multi-tenancy.column_names.tenant_foreign_key', 'tenant_id'))->constrained()->cascadeOnDelete();
        });

        // Add macro for tenant-scoped unique constraints
        Blueprint::macro('tenantUnique', function (string $column, ?string $indexName = null) {
            $tenantColumn = config('filament-multi-tenancy.column_names.tenant_foreign_key', 'tenant_id');
            $indexName = $indexName ?? "unique_{$tenantColumn}_{$column}";
            $this->unique([$tenantColumn, $column], $indexName);
            
            return $this;
        });

        // Add a custom validation rule
        Validator::extend('tenant_unique', function ($attribute, $value, $parameters, $validator) {
            $table = $parameters[0] ?? null;
            $idColumn = $parameters[1] ?? 'id';
            $ignoreId = $parameters[2] ?? null;
            
            if (!$table) {
                return false;
            }

            $tenantColumn = config('filament-multi-tenancy.column_names.tenant_foreign_key', 'tenant_id');
            $tenantId = tenant($tenantColumn);
            
            $query = \DB::table($table)->where($attribute, $value)->where($tenantColumn, $tenantId);
            
            if ($ignoreId) {
                $query->where($idColumn, '!=', $ignoreId);
            }
            
            return $query->count() === 0;
        });
    }
}