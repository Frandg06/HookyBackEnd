<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Role extends Model
{
    public const USER = 2;

    public const PREMIUM = 3;

    protected $fillable = ['id', 'name'];
}
