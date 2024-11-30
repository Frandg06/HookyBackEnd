<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersInteraction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['user_uid', 'iteraction_id', 'interaction_user_uid' , 'event_uid'];

    public function user() {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function interactionUser() {
        return $this->belongsTo(User::class, 'interaction_user_uid', 'uid');
    }

    public function event() {
        return $this->belongsTo(Event::class, 'event_uid', 'uid');
    }

    public function interaction() {
        return $this->belongsTo(Interaction::class, 'iteraction_id');
    }


    public function scopeUsersWithoutInteraction($query, $eventUid) {
        return $query->where('interaction_id', null)
        ->where('event_uid', $eventUid) 
        ->get()
        ->pluck('interaction_user_uid');
    }

    public function scopeUsersWithInteraction($query, $eventUid) {
        return $query->where('event_uid', $eventUid)
        ->whereNot('interaction_id', null)
        ->get()
        ->pluck('interaction_user_uid');
    }

}
