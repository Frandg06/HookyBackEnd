<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class NotificationsType extends Model
{
    public const LIKE_TYPE = 1;

    public const SUPER_LIKE_TYPE = 2;

    public const HOOK_TYPE = 3;

    public const MESSAGE_TYPE = 4;

    public const LIKE_TYPE_STR = 'like';

    public const SUPER_LIKE_TYPE_STR = 'superlike';

    public const HOOK_TYPE_STR = 'hook';

    public const MESSAGE_TYPE_STR = 'message';

    protected $fillable = ['id', 'name'];
}
