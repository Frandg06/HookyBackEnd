<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasUuids;
    protected $table = 'chats';
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    public $incrementing = false;


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
