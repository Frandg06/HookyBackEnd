<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SexualOrientation extends Model
{
    public const BISEXUAL = 1;

    public const HETEROSEXUAL = 2;

    public const HOMOSEXUAL = 3;

    protected $fillable = ['id', 'name'];
}
