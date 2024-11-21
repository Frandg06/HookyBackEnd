<?php

namespace App\Models\Traits;

trait HasVerified
{
    
    protected static function bootHasVerified()
    {
      static::creating(function ($model) {
          $model->verified = $model->checkIfVerified($model);
      });

      static::updating(function ($model) {
        $model->verified = $model->checkIfVerified($model);
      });
    }

    private function checkIfVerified($model) {
      return ($model->data_images && $model->data_interest && $model->data_complete)
        ?  true
        :  false;
    }
}
