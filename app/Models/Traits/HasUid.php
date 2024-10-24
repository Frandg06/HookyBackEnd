<?php

namespace App\Models\Traits;

trait HasUid
{
    public bool $uidMoreEntropy = false;
    
    protected static function bootHasUid()
    {
        static::creating(function ($model) {
            $model->uid = uniqid(mt_rand(), $model->uidMoreEntropy);
        });
    }
}
