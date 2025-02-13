<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasUid;

    protected $fillable = ['id', 'name', 'price', 'limit_users', 'limit_events'];

}
