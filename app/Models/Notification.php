<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasUid;

    public const TYPE_LIKE = "like";
    public const TYPE_SUPER_LIKE = "superlike";
    public const TYPE_HOOK = "hook";

    protected $fillable = [
        "id",
        "uid",
        "user_uid",
        "event_uid",
        "type",
        "data",
        "read_at",
        "created_at",
        "updated_at",
    ];
}
