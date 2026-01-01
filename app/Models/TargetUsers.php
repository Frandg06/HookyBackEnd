<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InteractionEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TargetUsers extends Model
{
    protected $table = 'target_users';

    protected $fillable = [
        'user_uid',
        'interaction',
        'target_user_uid',
        'event_uid',
        'created_at',
        'updated_at',
    ];

    public static function checkIsLike($uid, $auth)
    {
        return self::where('user_uid', $uid)
            ->where('target_user_uid', $auth->uid)
            ->where('interaction', InteractionEnum::LIKE)
            ->where('event_uid', $auth->event->uid)
            ->exists();
    }

    public static function checkIsSuperLike($uid, $auth)
    {
        return self::where('user_uid', $uid)
            ->where('target_user_uid', $auth->uid)
            ->where('interaction', InteractionEnum::SUPERLIKE)
            ->where('event_uid', $auth->event->uid)
            ->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function emitter(): BelongsTo
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

    protected function casts(): array
    {
        return [
            'interaction' => InteractionEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
