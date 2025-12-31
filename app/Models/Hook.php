<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Hook extends Model
{
    /** @use HasFactory<\Database\Factories\HookFactory> */
    use HasFactory;

    use HasUuids;

    public $incrementing = false;

    protected $table = 'hooks';

    protected $primaryKey = 'uid';

    protected $keyType = 'string';

    protected $fillable = [
        'uid',
        'user1_uid',
        'user2_uid',
        'event_uid',
    ];

    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_uid', 'uid');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_uid', 'uid');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_uid', 'uid');
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
