<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasUid;

    protected $fillable = [
        "id",
        "uid",
        "user_uid",
        "emitter_uid",
        "event_uid",
        "type_id",
        "msg",
        "read_at",
        "created_at",
        "updated_at",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public static function scopeGetLikeAndSuperLikeNotify($query, $reciber, $emitter, $event) {
        $query->where('user_uid', $reciber)
        ->where('emitter_uid', $emitter)
        ->where('event_uid', $event)
        ->whereIn('type_id', [NotificationsType::LIKE_TYPE, NotificationsType::SUPER_LIKE_TYPE])
        ->first(); 

    }
}
