<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Interaction extends Model
{
    public const SUPER_LIKE_ID = 1;

    public const LIKE_ID = 2;

    public const DISLIKE_ID = 3;

    public $timestamps = false;

    protected $fillable = ['id', 'name'];
}
