<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetUsers extends Model
{
    protected $table = 'target_users';

    protected $fillable = ['user_uid', 'interaction_id', 'target_user_uid', 'event_uid'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function interactionUser()
    {
        return $this->belongsTo(User::class, 'target_user_uid', 'uid');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_uid', 'uid');
    }

    public function interaction()
    {
        return $this->belongsTo(Interaction::class, 'iteraction_id');
    }

    public function scopeUsersWithoutInteraction($query, $eventUid)
    {
        return $query->where('interaction_id', null)
            ->where('event_uid', $eventUid)
            ->get()
            ->pluck('target_user_uid');
    }

    public function scopeUsersWithInteraction($query, $eventUid)
    {
        return $query->where('event_uid', $eventUid)
            ->whereNot('interaction_id', null)
            ->get()
            ->pluck('target_user_uid');
    }

    public static function scopeIsHook($query, $emitter, $reciber, $event)
    {
        return $query->where('user_uid', $reciber)
            ->where('target_user_uid', $emitter)
            ->whereIn('interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
            ->whereExists(function ($query) use ($reciber, $emitter, $event) {
                $query->from('target_users')
                    ->where('user_uid', $emitter)
                    ->where('target_user_uid', $reciber)
                    ->whereIn('interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
                    ->where('event_uid', $event);
            });
    }

    public static function checkIsLike($uid, $auth)
    {
        return self::where('user_uid', $uid)
            ->where('target_user_uid', $auth->uid)
            ->where('interaction_id', Interaction::LIKE_ID)
            ->where('event_uid', $auth->event->uid)
            ->exists();
    }

    public static function checkIsSuperLike($uid, $auth)
    {
        return self::where('user_uid', $uid)
            ->where('target_user_uid', $auth->uid)
            ->where('interaction_id', Interaction::SUPER_LIKE_ID)
            ->where('event_uid', $auth->event->uid)
            ->exists();
    }
}
