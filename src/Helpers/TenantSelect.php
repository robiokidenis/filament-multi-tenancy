<?php

namespace Robiokidenis\FilamentMultiTenancy\Helpers;

use Filament\Forms;
use Illuminate\Support\Facades\Config;

class TenantSelect
{
    public static function make($field = null, $relationship = null, $nameAttribute = null)
    {
        $field = $field ?? Config::get('filament-multi-tenancy.tenant_select.column', 'tenant_id');
        $relationship = $relationship ?? Config::get('filament-multi-tenancy.tenant_select.relationship', 'tenant');
        $nameAttribute = $nameAttribute ?? Config::get('filament-multi-tenancy.tenant_select.name_attribute', 'name');

        return Forms\Components\Select::make($field)
            ->relationship($relationship, $nameAttribute)
            ->required();
    }
}