<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Settings extends Model
{
    protected $fillable = [
        'user_uid',
        'new_like_notification',
        'new_superlike_notification',
        'new_message_notification',
        'event_start_email',
    ];

    protected $table = 'settings';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }
}
