<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    protected $fillable = ['user_uid', 'event_uid', 'logged_at', 'likes', 'super_likes'];

    public function scopeActiveEventData($query) {
        return $query->orderBy('logged_at', 'desc')->first();
    }
}
