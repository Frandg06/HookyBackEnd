<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasUuids;
    protected $table = 'chat_messages';
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'uid',
        'chat_uid',
        'sender_uid',
        'message',
        'read_at',
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'updated_at',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_uid', 'uid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_uid', 'uid');
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
