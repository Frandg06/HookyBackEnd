<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUid;
    protected $fillable = ['uid', 'st_date', 'end_date', 'company_uid', 'timezone', 'likes', 'super_likes']; 
    protected $hidden = ['created_at', 'updated_at', 'id'];

    public function scopeNextMontEvents($query)  { 
        $query->where('st_date', '>', Carbon::now())
        ->whereYear('st_date', Carbon::now()->year)
        ->whereMonth('st_date', Carbon::now()->month);
    }

    public function scopeFirstNextEvent($query)  { 
        $query->where('st_date', '>', Carbon::now())
            ->orderBy('st_date', 'asc');
    }

    public function scopeActiveEvent($query, $timezone)  {
        return $query->where('st_date', '<', Carbon::now($timezone))
            ->where('end_date', '>', Carbon::now($timezone))
            ->orderBy('st_date', 'asc');
    }

    public function scopeEventInSameDay($query, $date)  { 
        $query->where('st_date', '>', $date->format('Y-m-d'))
        ->whereYear('st_date', $date->year)
        ->whereMonth('st_date', $date->month)
        ->whereDay('st_date', $date->day);
    }
}
 