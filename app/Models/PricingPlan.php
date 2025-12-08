<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class PricingPlan extends Model
{
    use HasUuids;

    protected $fillable = ['id', 'name', 'price', 'limit_users', 'limit_events'];

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
