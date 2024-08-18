<?php

namespace Robiokidenis\FilamentMultiTenancy\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TenantUser extends Pivot
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('multi-tenancy.table_names.tenant_user');
    }

    protected $fillable = [
        'tenant_id',
        'user_id',
        'role',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, config('multi-tenancy.column_names.tenant_foreign_key'));
    }

    public function user()
    {
        return $this->belongsTo(config('multi-tenancy.user_model'), config('multi-tenancy.column_names.user_foreign_key'));
    }
}