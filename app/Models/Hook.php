<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
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

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
