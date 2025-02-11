<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{


    public const ROLE_USER = 2;
    public const ROLE_PREMIUM = 3;
    
    use HasFactory;
}
