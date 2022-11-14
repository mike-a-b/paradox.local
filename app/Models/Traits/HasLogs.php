<?php

namespace App\Models\Traits;

use App\Models\Log;

trait HasLogs
{
    protected static function boot()
    {
        parent::boot();

        static::updating(function($model) {
            Log::create([
                'user_id' => auth()->user()?->id ?? 0,
                'table_name' => $model->getTable(),
                'model_id' => $model->id,
                'title' => 'update',
                'data' =>  json_encode($model->getDirty())
            ]);
        });

        static::created(function($model) {
            Log::create([
                'user_id' => auth()->user()?->id ?? 0,
                'table_name' => $model->getTable(),
                'model_id' => $model->id,
                'title' => 'create',
                'data' =>  json_encode($model->getDirty())
            ]);
        });

        static::deleted(function($model) {
            Log::create([
                'user_id' => auth()->user()?->id ?? 0,
                'table_name' => $model->getTable(),
                'model_id' => $model->id,
                'title' => 'delete',
                'data' =>  json_encode($model->getDirty())
            ]);
        });
    }
}
