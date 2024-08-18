<?php

namespace Robiokidenis\FilamentMultiTenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'website',
        'address',
        'logo',
        'timezone',
        'settings',
        'plan',
        'trial_ends_at',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'settings' => 'array',
        'trial_ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getTable()
    {
        return config('multi-tenancy.table_names.tenants', parent::getTable());
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('multi-tenancy.user_model'),
            config('multi-tenancy.table_names.tenant_user'),
            config('multi-tenancy.column_names.tenant_foreign_key'),
            config('multi-tenancy.column_names.user_foreign_key')
        )->withTimestamps();
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(TenantInvitation::class, config('multi-tenancy.column_names.tenant_foreign_key'));
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(config('multi-tenancy.user_model'), 'user_id');
    }
}
