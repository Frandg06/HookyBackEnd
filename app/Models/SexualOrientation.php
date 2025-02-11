<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SexualOrientation extends Model
{
    const BISEXUAL = 1;
    const HETEROSEXUAL = 2;
    const HOMOSEXUAL = 3;
    use HasFactory;
}
