<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{

    const FEMALE = 1;
    const MALE = 2;
    use HasFactory;
}
