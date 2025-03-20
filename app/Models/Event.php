<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\HasUid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasUid, Filterable, HasFactory;

    protected $fillable = ['uid', 'st_date', 'end_date', 'company_uid', 'timezone', 'likes', 'super_likes', 'name', 'colors']; 
    protected $hidden = ['created_at', 'updated_at', 'id'];

    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'company_uid', 'uid');
    }

    public function users(): HasMany {
        return $this->hasMany(UserEvent::class, 'event_uid', 'uid');
    }

    public function tickets(): HasMany {
        return $this->hasMany(Ticket::class, 'event_uid', 'uid');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'event_uid', 'uid');
    }
    
    public function scopeNextMontEvents($query)  { 
        $query->where('st_date', '>', Carbon::now())
        ->whereYear('st_date', Carbon::now()->year)
        ->whereMonth('st_date', Carbon::now()->month);
    }

    public function scopeFirstNextEvent($query, $tz)  { 
        $query->where('st_date', '>', Carbon::now($tz))
            ->orderBy('st_date', 'asc');
    }

    public function scopeActiveEvent($query, $timezone)  {
        return $query->where('st_date', '<', Carbon::now($timezone))
            ->where('end_date', '>', Carbon::now($timezone))
            ->orderBy('st_date', 'asc');

    }

    public function scopeActiveOrNextEvent($query, $timezone)  {
        return $query->where(function ($sub) use ($timezone) {
                $sub->where('st_date', '<', now($timezone))
                ->where('end_date', '>', now($timezone));
            })->orWhere(function ($sub) use ($timezone) {
                $sub->where('st_date', '>', now($timezone));
            })
            ->orderBy('st_date', 'asc');

    }

    public function scopeLastEvent($query, $timezone)  {
        return $query->where(function ($sub) use ($timezone) {
                $sub->where('st_date', '<', now($timezone))
                ->where('end_date', '>', now($timezone));
            })
            ->orWhere(function ($sub) use ($timezone) {
                $sub->where('st_date', '<', now($timezone));
            })
            ->orderBy('st_date', 'desc');
    }

    public function scopeEventInSameDay($query, $date)  { 
        $query->where('st_date', '>', $date->format('Y-m-d'))
        ->whereYear('st_date', $date->year)
        ->whereMonth('st_date', $date->month)
        ->whereDay('st_date', $date->day);
    }

    public function getTotalUsersAttribute() {
        return $this->users()->count();
    }

    public function getTotalIncomesAttribute() {
        return $this->tickets()->count() * $this->company->average_ticket_price;
    }

    public function getAvgAgeAttribute() {
        return $this->users()
            ->join('users', 'user_events.user_uid', '=', 'users.uid')
            ->selectRaw('AVG(EXTRACT(YEAR FROM AGE(users.born_date))) as avg_age')
            ->value('avg_age');
    }

    public function getPercentsAttribute(): array {

        $total = $this->users()->count();

        if($total == 0) return [
            'males' => 0,
            'females' => 0,
        ];

        $males = $this->users()
            ->join('users', 'user_events.user_uid', '=', 'users.uid')
            ->where('users.gender_id', Gender::MALE)
            ->count();

        $females = $this->users()
            ->join('users', 'user_events.user_uid', '=', 'users.uid')
            ->where('users.gender_id', Gender::FEMALE)
            ->count() ;


        return [
            'males' => $males,
            'females' => $females,
        ];
    }

    public function getHooksAttribute()  { 
        return $this->notifications()->where('type_id', '=', NotificationsType::HOOK_TYPE)->count() / 2;
    }
}
 