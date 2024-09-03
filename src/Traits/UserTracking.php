<?php

namespace Robiokidenis\FilamentMultiTenancy\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait UserTracking
{
    public static function bootUserTracking()
    {
        static::creating(function (Model $model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function (Model $model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function (Model $model) {
            if (in_array(SoftDeletes::class, class_uses_recursive($model)) && auth()->check()) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }

    public function initializeUserTracking()
    {
        $this->fillable[] = 'created_by';
        $this->fillable[] = 'updated_by';
        $this->fillable[] = 'deleted_by';
    }
}
