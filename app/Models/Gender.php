<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Gender extends Model
{
    public const FEMALE = 1;

    public const MALE = 2;

    protected $fillable = ['id', 'name'];
}
