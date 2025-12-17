<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class TimeZone extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'utc_offset', 'uid'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
