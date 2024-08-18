<?php

namespace Robiokidenis\FilamentMultiTenancy\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Robiokidenis\FilamentMultiTenancy\Models\Tenant;

trait HasTenants
{
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(
            Tenant::class,
            config('multi-tenancy.table_names.tenant_user'),
            config('multi-tenancy.column_names.user_foreign_key'),
            config('multi-tenancy.column_names.tenant_foreign_key')
        )->withTimestamps();
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
