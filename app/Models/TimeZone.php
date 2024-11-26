<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{
    use HasUid;

    protected $fillable = ['name', 'utc_offset', 'uid'];
    protected $hidden = ['id', 'created_at', 'updated_at'];
}
