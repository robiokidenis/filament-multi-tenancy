<?php

// config for Robiokidenis/FilamentMultiTenancy
return [
    'user_model' => \App\Models\User::class,
    'tenant_model' => \Robiokidenis\FilamentMultiTenancy\Models\Tenant::class,
    'invitation_model' => \Robiokidenis\FilamentMultiTenancy\Models\TenantInvitation::class,

    'table_names' => [
        'tenants' => 'tenants',
        'tenant_user' => 'tenant_user',
        'tenant_invitations' => 'tenant_invitations',
    ],

    'column_names' => [
        'tenant_foreign_key' => 'tenant_id',
        'user_foreign_key' => 'user_id',
    ],
];
