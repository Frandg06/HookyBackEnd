<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasUid;

    protected $fillable = [
        'company_uid',
        'code',
        'redeemed',
        'super_likes',
        'likes'
    ];
    protected $hidden = [ 'updated_at', 'id'];

    public function scopeTicketsCountThisMonth($query) {
        return $query->where('redeemed', true)
        ->whereDate('redeemed_at', Carbon::now()->format('Y-m-d'));
    }

    public function scopeTicketsCountLastMonth($query) {
        return $query->where('redeemed', true)
        ->whereDate('redeemed_at', Carbon::now()->subMonth()->format('Y-m-d'));   
    }
}
