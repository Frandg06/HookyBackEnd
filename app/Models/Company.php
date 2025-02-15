<?php

namespace App\Models;

use App\Http\Resources\AuthCompanyResource;
use App\Http\Resources\UsersToTableResource;
use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function events() : HasMany {
        return $this->hasMany(Event::class, 'company_uid', 'uid');
    }
    
    public function tickets() : HasMany {
        return $this->hasMany(Ticket::class, 'company_uid', 'uid');
    }

    public function getlinkAttribute() {
        return config("app.front_url") . "/?company=" . Crypt::encrypt($this->uid);
    }

    public function timezone() : BelongsTo {
        return $this->belongsTo(TimeZone::class, 'timezone_uid', 'uid');
    }

    public function pricing_plan() : BelongsTo {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_uid', 'uid');
    }

    public function checkEventLimit() {
        $events = $this->events()->nextMontEvents()->count();
        $limit = $this->pricing_plan->limit_events;
        return $events < $limit;
    }

    public function scopeResource() {
        return AuthCompanyResource::make($this);
    }

    public function checkEventInSameDay($date) {
        $events = $this->events()->eventInSameDay($date)->count();
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

    public function getTotalUsersAttribute() {
        return $this->events()
        ->join('user_events', 'events.uid', '=', 'user_events.event_uid')
        ->distinct('user_events.user_uid')
        ->count('user_events.user_uid');
    }

    public function getLastFiveUsersAttribute() {
        
        $ids = $this->events()
        ->join('user_events', 'events.uid', '=', 'user_events.event_uid')
        ->join('users', 'user_events.user_uid', '=', 'users.uid')
        ->select('users.*')
        ->orderBy('users.created_at', 'desc')
        // ->limit(5)
        ->pluck('users.uid');
        $users = User::whereIn('uid', $ids)->get();
        
        return UsersToTableResource::collection($users);
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [
            'uid' => $this->uid,
        ];
    }
}
