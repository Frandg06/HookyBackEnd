<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    protected $fillable = ['user_uid', 'event_uid', 'logged_at', 'likes', 'super_likes'];

    public function event() {
        return $this->belongsTo(Event::class, 'event_uid', 'uid');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function scopeActiveEventData($query) {
        return $query->orderBy('logged_at', 'desc')->first();
    }

    public function scopeGetCompanyByEvent($query) { 
        return $query->activeEventData()->event->company_uid;
    }

}
