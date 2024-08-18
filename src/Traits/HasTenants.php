<?php

namespace Robiokidenis\FilamentMultiTenancy\Traits;

use Filament\Panel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Robiokidenis\FilamentMultiTenancy\Models\Tenant;

trait HasTenants
{

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenants->contains($tenant);
    }


    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(
            Tenant::class,
            config('multi-tenancy.table_names.tenant_user'),
            config('multi-tenancy.column_names.user_foreign_key'),
            config('multi-tenancy.column_names.tenant_foreign_key')
        )->withTimestamps();
    }



    public function getTenants(Panel $panel): Collection
    {
        return $this->tenants;
    }


    public function ownedTenants()
    {
        return $this->hasMany(Tenant::class, 'user_id');
    }

    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function setCurrentTenant(Tenant $tenant)
    {
        $this->current_tenant_id = $tenant->id;
        $this->save();
    }
}
