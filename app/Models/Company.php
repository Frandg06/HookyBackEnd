<?php

namespace App\Models;

use App\Http\Resources\AuthCompanyResource;
use App\Http\Resources\UsersToTableResource;
use App\Models\Traits\HasUid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Company extends Authenticatable implements JWTSubject
{
    use HasFactory, HasUid;

    protected $table = 'companies';
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'password',
        'timezone_uid',
        'average_ticket_price',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'company_uid', 'uid');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'company_uid', 'uid');
    }

    public function getlinkAttribute()
    {
        return config('app.front_url') . '/?company=' . Crypt::encrypt($this->uid);
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(TimeZone::class, 'timezone_uid', 'uid');
    }

    public function pricing_plan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_uid', 'uid');
    }

    public function checkEventLimit($st_date, $uid)
    {
        $limit = $this->pricing_plan->limit_events;
        $events = $this->events()
            ->when($uid, function ($query) use ($uid) {
                $query->whereNot('uid', $uid);
            })
            ->whereDate('st_date', '>=', $st_date->startOfMonth())
            ->whereDate('st_date', '<=', $st_date->endOfMonth())->count();
        return $events < $limit;
    }

    public function scopeResource()
    {
        return AuthCompanyResource::make($this);
    }

    public function users()
    {
        return User::whereIn('uid', function ($query) {
            $query->select('user_uid')
                ->from('user_events')
                ->whereIn('event_uid', function ($q) {
                    $q->select('uid')
                        ->from('events')
                        ->where('company_uid', $this->uid);
                });
        });
    }

    public function checkEveventsInSameTime($st_date, $end_date, $uid)
    {
        $events = $this->events()
            ->when($uid, function ($query) use ($uid) {
                $query->whereNot('uid', $uid);
            })
            ->where(function ($query) use ($st_date, $end_date) {
                $query->where(function ($query2) use ($st_date) {
                    $query2->whereDate('st_date', '>=', $st_date)
                        ->whereDate('end_date', '<=', $st_date);
                })->orWhere(function ($query3) use ($end_date) {
                    $query3->whereDate('st_date', '>=', $end_date)
                        ->whereDate('end_date', '<=', $end_date);
                });
            })->count();
        return $events > 0;
    }

    public function getActiveEventAttribute()
    {
        return $this->events()->activeEvent($this->timezone->name)->first();
    }

    public function getNextEventAttribute()
    {
        return $this->events()->firstNextEvent($this->timezone->name)->first();
    }

    public function getLimitUsersAttribute()
    {
        return $this->pricing_plan->limit_users;
    }

    public function getLastEventAttribute()
    {
        return $this->events()->lastEvent($this->timezone->name)->first();
    }

    public function getTotalUsersAttribute()
    {
        $query = $this->events()
            ->join('user_events', 'events.uid', '=', 'user_events.event_uid')
            ->distinct('user_events.user_uid');

        $userCurrentMonth = (clone $query)->whereBetween('user_events.logged_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->count('user_events.user_uid');

        $userLastMonth = (clone $query)->whereBetween('user_events.logged_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count('user_events.user_uid');


        return [
            'count' => $userCurrentMonth,
            'user_last_month' =>  $userLastMonth,
            'user_current_month' => $userCurrentMonth,
        ];
    }

    public function getTotalTicketsAttribute($query)
    {
        $query = $this->tickets()->where('redeemed', true);

        $ticketCurrentMonth = (clone $query)->whereBetween('redeemed_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->count();

        $tickeLastMonth = (clone $query)->whereBetween('redeemed_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count();


        return [
            'count' => $ticketCurrentMonth,
            'ticket_last_month' =>  $tickeLastMonth,
            'ticket_current_month' => $ticketCurrentMonth,
        ];
    }

    public function getRecentEntriesAttribute()
    {
        return $this->events()
            ->join('user_events', 'events.uid', '=', 'user_events.event_uid')
            ->selectRaw("TO_CHAR(DATE_TRUNC('hour', user_events.logged_at), 'HH24:MI') as hour, COUNT(DISTINCT user_events.user_uid) as count")
            ->where('user_events.logged_at', '>=', now()->subHours(8))
            ->where('user_events.logged_at', '<=', now())
            ->groupByRaw("DATE_TRUNC('hour', user_events.logged_at)")
            ->orderByRaw("DATE_TRUNC('hour', user_events.logged_at)")
            ->get();
    }
    public function getLastSevenEventsAttribute()
    {
        $events = $this->events()
            ->where('st_date', '<=', now())
            ->orderBy('st_date', 'desc')
            ->limit(7)
            ->get();

        return [
            'labels' => $events->map(function ($event) {
                return Carbon::parse($event->st_date)->format('d/m/Y');
            }),
            'event_names' => $events->pluck('name')->toArray(),
            'data' => [
                [
                    'name' => 'Usuarios',
                    'data' => $events->map(function ($event) {
                        return $event->users()->count();
                    })
                ],
                [
                    'name' => 'Ingresos',
                    'data' => $events->map(function ($event) {
                        return $event->total_incomes;
                    })
                ],
            ]
        ];
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'uid' => $this->uid,
        ];
    }
}
