<?php

namespace Robiokidenis\FilamentMultiTenancy\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Robiokidenis\FilamentMultiTenancy\FilamentMultiTenancy
 */
class FilamentMultiTenancy extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Robiokidenis\FilamentMultiTenancy\FilamentMultiTenancy::class;
    }
}
