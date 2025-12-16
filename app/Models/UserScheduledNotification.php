<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class UserScheduledNotification extends Model
{
    protected $fillable = [
        'user_uid',
        'event_uid',
        'scheduled_at',
        'status',
    ];
}
