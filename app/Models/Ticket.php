<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use App\Models\Traits\Sortable;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Ticket extends Model
{
    use Filterable;
    use HasFactory;
    use HasUuids;
    use Sortable;

    protected $fillable = [
        'company_uid',
        'code',
        'redeemed',
        'redeemed_at',
        'superlikes',
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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_uid', 'uid');
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
