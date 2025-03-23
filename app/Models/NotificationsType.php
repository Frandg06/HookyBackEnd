<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationsType extends Model
{
    const LIKE_TYPE = 1;
    const SUPER_LIKE_TYPE = 2;
    const HOOK_TYPE = 3;
    const MESSAGE_TYPE = 4;
    const LIKE_TYPE_STR = 'like';
    const SUPER_LIKE_TYPE_STR = 'superlike';
    const HOOK_TYPE_STR = 'hook';
    const MESSAGE_TYPE_STR = 'message';

    protected $fillable = ['id', 'name'];
}
