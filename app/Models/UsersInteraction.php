<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersInteraction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['user_id', 'iteraction_id', 'interaction_user_id'];

}
