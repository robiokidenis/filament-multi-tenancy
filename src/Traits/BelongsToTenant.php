<?php

namespace Robiokidenis\FilamentMultiTenancy\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Robiokidenis\FilamentMultiTenancy\Models\Tenant;

trait BelongsToTenant
{
    public static function bootBelongsToTenant()
    {
        static::creating(function (Model $model) {
            if (!$model->getAttribute(self::getTenantForeignKey()) && self::getTenant()) {
                $model->setAttribute(self::getTenantForeignKey(), self::getTenant()->id);
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            $builder->where(self::getTenantForeignKey(), self::getTenant()->id);
        });
    }

    public function tenant()
    {
        return $this->belongsTo(config('multi-tenancy.tenant_model'), self::getTenantForeignKey());
    }

    public static function getTenant(): ?Tenant
    {
        if (Auth::check()) {
            return Auth::user()->currentTenant;
        }
        
        return null;
    }

    public static function getTenantForeignKey(): string
    {
        return config('multi-tenancy.column_names.tenant_foreign_key', 'tenant_id');
    }

    public function scopeBelongsToTenant($query, $tenant = null)
    {
        $tenantId = $tenant instanceof Tenant ? $tenant->id : $tenant;

        return $query->where(self::getTenantForeignKey(), $tenantId ?: self::getTenant()->id);
    }
}