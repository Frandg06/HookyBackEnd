<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'utc_offset', 'uid'];
    protected $hidden = ['id', 'created_at', 'updated_at'];


    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
