<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const USER = 2;
    public const PREMIUM = 3;

    protected $fillable = ['id', 'name'];
    
    use HasFactory;
}
