<?php

namespace Robiokidenis\FilamentMultiTenancy\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Robiokidenis\FilamentMultiTenancy\Helpers\TenantHelper;
use Robiokidenis\FilamentMultiTenancy\Models\Tenant;

trait HasTenantScope
{
    public static function bootBelongsToTenant()
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            TenantHelper::scopeToTenant($query);
        });

        static::saving(function (Model $model) {
            TenantHelper::setTenantIdOnModel($model);
        });
    }


    public function tenant()
    {
        $tenantModel = config('filament-multi-tenancy.tenant_model');

        if (!$tenantModel) {
            throw new \Exception('Tenant model not configured. Please check your filament-multi-tenancy config file.');
        }

        return $this->belongsTo($tenantModel, self::getTenantForeignKey());
    }



    public static function getTenantForeignKey(): string
    {
        return config('filament-multi-tenancy.column_names.tenant_foreign_key', 'tenant_id');
    }

}
