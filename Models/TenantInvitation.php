<?php

namespace Robiokidenis\FilamentMultiTenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantInvitation extends Model
{
    protected $fillable = [
        'tenant_id',
        'email',
        'role',
        'token',
        'expires_at',
        'invited_by',
        'accepted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function getTable()
    {
        return config('multi-tenancy.table_names.tenant_invitations', parent::getTable());
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, config('multi-tenancy.column_names.tenant_foreign_key'));
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(config('multi-tenancy.user_model'), 'invited_by');
    }
}