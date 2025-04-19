<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    public const FEMALE = 1;

    public const MALE = 2;

    protected $fillable = ['id', 'name'];
}
