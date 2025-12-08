<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Notification extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'uid',
        'user_uid',
        'emitter_uid',
        'event_uid',
        'type_id',
        'msg',
        'read_at',
        'created_at',
        'updated_at',
    ];

    public static function scopeGetLikeAndSuperLikeNotify($query, $reciber, $emitter, $event)
    {
        $query->where('user_uid', $reciber)
            ->where('emitter_uid', $emitter)
            ->where('event_uid', $event)
            ->whereIn('type_id', [NotificationsType::LIKE_TYPE, NotificationsType::SUPER_LIKE_TYPE])
            ->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function emitter_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emitter_uid', 'uid');
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
