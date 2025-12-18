<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Product extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'price',
        'price_id',
        'limit_users',
        'limit_events',
        'ticket_limit',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
