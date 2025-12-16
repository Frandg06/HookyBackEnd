<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Event
 *
 * @property string $uid
 * @property string $st_date
 * @property string $end_date
 * @property string $company_uid
 * @property string $timezone
 * @property int $likes
 * @property int $super_likes
 * @property string $name
 * @property array|null $colors
 * @property string $code
 * @property string $room_name
 * @property string $city
 * @property string|null $banner_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Chat> $chats
 * @property-read int|null $chats_count
 * @property-read bool $is_active
 * @property-read bool $is_finished
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, UserEvent> $users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Event activeEvent($timezone)
 * @method static \Illuminate\Database\Eloquent\Builder|Event activeOrNextEvent($timezone)
 * @method static \Illuminate\Database\Eloquent\Builder|Event eventInSameDay($date)
 * @method static \Illuminate\Database\Eloquent\Builder|Event firstNextEvent($tz)
 * @method static \Illuminate\Database\Eloquent\Builder|Event lastEvent($timezone)
 * @method static \Illuminate\Database\Eloquent\Builder|Event nextMontEvents()
 * @method static \Illuminate\Database\Eloquent\Builder|Event filter(\App\Http\Filters\BaseFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Event sort(\App\Http\Orders\BaseOrdenator $order)
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 */
final class Event extends Model
{
    use Filterable;
    use HasFactory;
    use HasUuids;
    use Sortable;

    public $incrementing = false;

    protected $table = 'events';

    protected $primaryKey = 'uid';

    protected $keyType = 'string';

    protected $fillable = [
        'uid',
        'st_date',
        'end_date',
        'company_uid',
        'timezone',
        'likes',
        'super_likes',
        'name',
        'colors',
        'code',
        'room_name',
        'city',
        'banner_image',
    ];

    protected $hidden = ['created_at', 'updated_at', 'id'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_uid', 'uid');
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserEvent::class, 'event_uid', 'uid');
    }

    public function users2(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_events', 'event_uid', 'user_uid');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'event_uid', 'uid');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'event_uid', 'uid');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'event_uid', 'uid');
    }

    public function scheduledNotifications(): HasMany
    {
        return $this->hasMany(UserScheduledNotification::class, 'event_uid', 'uid');
    }

    public function getIsActiveAttribute()
    {
        return $this->st_date <= now($this->timezone) && $this->end_date >= now($this->timezone);
    }

    public function getIsFinishedAttribute()
    {
        $end_date = Carbon::parse($this->end_date);
        $now = now($this->timezone);

        return $now->gt($end_date);
    }

    public function scopeNextMontEvents($query)
    {
        $query->where('st_date', '>', Carbon::now())
            ->whereYear('st_date', Carbon::now()->year)
            ->whereMonth('st_date', Carbon::now()->month);
    }

    public function scopeFirstNextEvent($query, $tz)
    {
        $query->where('st_date', '>', Carbon::now($tz))
            ->orderBy('st_date', 'asc');
    }

    public function scopeActiveEvent($query, $timezone)
    {
        return $query->where('st_date', '<', now($timezone))
            ->where('end_date', '>', now($timezone))
            ->orderBy('st_date', 'asc');
    }

    public function scopeActiveOrNextEvent($query, $timezone)
    {
        return $query->where(function ($sub) use ($timezone) {
            $sub->where('st_date', '<', now($timezone))
                ->where('end_date', '>', now($timezone));
        })->orWhere(function ($sub) use ($timezone) {
            $sub->where('st_date', '>', now($timezone));
        })
            ->orderBy('st_date', 'asc');
    }

    public function scopeLastEvent($query, $timezone)
    {
        return $query->where(function ($sub) use ($timezone) {
            $sub->where('st_date', '<', now($timezone))
                ->where('end_date', '>', now($timezone));
        })
            ->orWhere(function ($sub) use ($timezone) {
                $sub->where('st_date', '<', now($timezone));
            })
            ->orderBy('st_date', 'desc');
    }

    public function scopeEventInSameDay($query, $date)
    {
        $query->where('st_date', '>', $date->format('Y-m-d'))
            ->whereYear('st_date', $date->year)
            ->whereMonth('st_date', $date->month)
            ->whereDay('st_date', $date->day);
    }

    public function getLinkAttribute()
    {
        return config('app.front_url').'/event?e='.$this->uid;
    }

    public function getTotalUsersAttribute()
    {
        return $this->users2()->count();
    }

    public function getTotalIncomesAttribute()
    {
        return $this->tickets()->where('redeemed', true)->sum('price');
    }

    public function getAvgAgeAttribute()
    {
        return (float) $this->users2()
            ->selectRaw('AVG(EXTRACT(YEAR FROM AGE(users.born_date))) as avg_age')
            ->value('avg_age');
    }

    public function getPercentsAttribute(): array
    {

        $total = $this->users2()->count();

        if ($total === 0) {
            return [
                'males' => 0,
                'females' => 0,
            ];
        }

        $males = $this->users2()
            ->where('users.gender_id', Gender::MALE)
            ->count();

        $females = $this->users2()
            ->where('users.gender_id', Gender::FEMALE)
            ->count();

        return [
            'males' => $males,
            'females' => $females,
        ];
    }

    public function getHooksAttribute()
    {
        return $this->notifications()->where('type_id', '=', NotificationsType::HOOK_TYPE)->count() / 2;
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
