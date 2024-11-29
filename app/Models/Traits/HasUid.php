<?php

namespace App\Models\Traits;
use Illuminate\Support\Str;
trait HasUid
{   
    protected static function bootHasUid()
    {
        static::creating(function ($model) {
            $model->uid = Str::uuid();
        });
    }
}
