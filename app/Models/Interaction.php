<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    public const SUPER_LIKE_ID = 1;
    public const LIKE_ID = 2;
    public const DISLIKE_ID = 3;
    
    use HasFactory;

    public $timestamps = false;
}
