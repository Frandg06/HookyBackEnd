<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasUid
{
    protected static function bootHasUid()
    {
        static::creating(function ($model) {
            if ($model->uid) {
                return;
            }

            $model->uid = Str::uuid();
        });
    }
}
