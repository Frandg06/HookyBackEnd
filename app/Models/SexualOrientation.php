<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class SexualOrientation extends Model
{
    public const BISEXUAL = 1;

    public const HETEROSEXUAL = 2;

    public const HOMOSEXUAL = 3;

    protected $fillable = ['id', 'name'];
}
