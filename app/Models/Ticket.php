<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use App\Models\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasUid, HasFactory, Sortable, Sortable;

    protected $fillable = [
        'company_uid',
        'code',
        'redeemed',
        'redeemed_at',
        'super_likes',
        'likes',
        'user_uid',
        'event_uid',
        'name',
        'price',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function scopeTicketsCountThisMonth($query): Builder
    {
        return $query->where('redeemed', true)
            ->whereDate('redeemed_at', Carbon::now()->format('Y-m-d'));
    }

    public function scopeTicketsCountLastMonth($query): Builder
    {
        return $query->where('redeemed', true)
            ->whereDate('redeemed_at', Carbon::now()->subMonth()->format('Y-m-d'));
    }

    public function scopeGetTicketByCompanyEventAndCode($query, $company_uid, $code)
    {
        return $query->where('company_uid', $company_uid)
            ->where('code', $code)
            ->where('redeemed', false);
    }
}
