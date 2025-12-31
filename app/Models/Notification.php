<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Notification extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $primaryKey = 'uid';

    protected $keyType = 'string';

    protected $fillable = [
        'uid',
        'user_uid',
        'event_uid',
        'type',
        'read_at',
        'created_at',
        'updated_at',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(NotificationsType::class, 'type_id', 'id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_uid', 'uid');
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

    protected function casts(): array
    {
        return [
            'read_at' => 'bool',
            'type' => NotificationTypeEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
