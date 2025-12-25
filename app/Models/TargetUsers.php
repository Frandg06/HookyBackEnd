<?php

declare(strict_types=1);

namespace App\Models;

use App\Dtos\InteractionDto;
use Illuminate\Database\Eloquent\Model;

final class TargetUsers extends Model
{
    protected $table = 'target_users';

    protected $fillable = ['user_uid', 'interaction_id', 'target_user_uid', 'event_uid'];

    public static function scopeIsHook($query, InteractionDto $target)
    {
        return $query->where('user_uid', $target->target_user_uid)
            ->where('target_user_uid', $target->user_uid)
            ->whereIn('interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
            ->whereExists(function ($query) use ($target) {
                $query->from('target_users')
                    ->where('user_uid', $target->user_uid)
                    ->where('target_user_uid', $target->target_user_uid)
                    ->whereIn('interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
                    ->where('event_uid', $target->event_uid);
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function targetUser()
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
}
