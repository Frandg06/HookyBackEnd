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
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Company extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use HasUid;

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
        'website',
        'cif',
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

    public function pricingPlan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_uid', 'uid');
    }

    public function checkEventLimit($st_date, $uid)
    {
        $limit = $this->pricingPlan->limit_events;
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
        return $this->pricingPlan->limit_users;
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


        $actual_data = (clone $query)->whereBetween('user_events.created_at', [
            now()->subMonths(6)->format('Y-m-d H:i:s'),
            now()->format('Y-m-d H:i:s')
        ])->count('user_events.user_uid');


        $usersLastSixMonths = (clone $query)->whereBetween('user_events.created_at', [
            now()->subMonths(12)->format('Y-m-d H:i:s'),
            now()->subMonth(6)->format('Y-m-d H:i:s')
        ])->count('user_events.user_uid');

        $percentage = $actual_data / ($usersLastSixMonths || 1) * 100;

        return [
            'data' =>  $actual_data,
            'percentage' => $percentage,
        ];
    }

    public function getIncomesAttribute($query)
    {
        $query = $this->tickets()->where('redeemed', true);

        $actual_data = (clone $query)->whereBetween('redeemed_at', [
            now()->subMonth(6),
            now()
        ])->sum('price');

        $past_incomes = (clone $query)->whereBetween('redeemed_at', [
            now()->subMonth(12),
            now()->subMonth(6),
        ])->sum('price');

        $percentage = $actual_data / ($past_incomes || 1) * 100;
        return [
            'data' =>  $actual_data,
            'percentage' => $percentage,
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
