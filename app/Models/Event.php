<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUid;
    protected $fillable = ['st_date', 'end_date', 'company_id', 'uid', 'timezone', 'likes', 'super_likes'];
    protected $hidden = ['created_at', 'updated_at', 'id'];
}
 