<?php

namespace Robiokidenis\FilamentMultiTenancy\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TenantHelper
{
    public static function setTenant($tenantId, $isQuiet = true)
    {
        $tenantClass = self::getTenantClass();
        $tenant = $tenantClass::find($tenantId);

        if (! $tenant) {
            Log::error("No tenant found with ID: $tenantId");

            return null;
        }

        self::filament()->setTenant($tenant, $isQuiet);
    }

    public static function filament()
    {
        return app('filament');
    }

    public static function getRelationshipColumnKey(): string
    {
        $relationshipName = Config::get('filament-multi-tenancy.tenant_select.relationship', 'tenant');
        $columnName = Config::get('filament-multi-tenancy.tenant_select.name_attribute', 'name');

        return Str::of($relationshipName)->append('.', $columnName)->toString();
    }

    public static function getLabel(): string
    {
        return Str::of(Config::get('filament-multi-tenancy.tenant_select.label', 'tenant'))
            ->singular()
            ->ucfirst()
            ->toString();
    }

    public static function getRelationshipName(): string
    {
        return Config::get('filament-multi-tenancy.tenant_select.relationship', 'tenant');
    }

    public static function getTenantTitleAttribute(): string
    {
        return Config::get('filament-multi-tenancy.tenant_select.name_attribute', 'name');
    }

    public static function getTenantIdFromModel(Model $model): ?string
    {
        $foreignKey = self::getTenantForeignKey();

        return $model->getAttribute($foreignKey);
    }

    public static function getTenantForeignKey(): string
    {
        return Config::get('filament-multi-tenancy.column_names.tenant_foreign_key', 'tenant_id');
    }

    public static function getTenantClass(): string
    {
        return Config::get('filament-multi-tenancy.tenant_model', \Robiokidenis\FilamentMultiTenancy\Models\Tenant::class);
    }

    public static function getCurrentTenantId(): ?string
    {
        return filament()->getTenant()?->id;
    }

    public static function getCurrentTenantSlug(): ?string
    {
        return filament()->getTenant()?->slug;
    }

    public static function scopeToTenant($query)
    {
        $tenantId = self::getCurrentTenantId();
        if ($tenantId) {
            $query->where(self::getTenantForeignKey(), $tenantId);
        }

        return $query;
    }

    public static function setTenantIdOnModel(Model $model): void
    {
        $tenantForeignKey = self::getTenantForeignKey();
        
        if (! $model->{$tenantForeignKey} && ($tenantId = self::getCurrentTenantId())) {
            $model->{$tenantForeignKey} = $tenantId;
           
        }
    }
}
