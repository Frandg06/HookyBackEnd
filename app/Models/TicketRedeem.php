<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class TicketRedeem extends Model
{
    use HasUid;

    protected $fillable = [	
        "uid",
        "ticket_uid",
        "user_uid",
        "event_uid",
        "created_at",
        "updated_at",
    ];
}
