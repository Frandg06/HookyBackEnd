<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUid;
    protected $fillable = ['st_date', 'en_date', 'company_id'];
}
 