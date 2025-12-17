<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Chat
 *
 * @property string $uid
 * @property string $user1_uid
 * @property string $user2_uid
 * @property string|null $event_uid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereEventUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereUser1Uid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereUser2Uid($value)
 *
 * @mixin \Eloquent
 */
final class Chat extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $table = 'chats';

    protected $primaryKey = 'uid';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'uid',
        'user1_uid',
        'user2_uid',
        'event_uid',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_uid', 'uid');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_uid', 'uid');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_uid', 'uid');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_uid', 'uid');
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }

    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }
}
