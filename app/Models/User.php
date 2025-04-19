<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class  User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUuids, Filterable, Sortable;

    protected $table = 'users';
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surnames',
        'email',
        'password',
        'gender_id',
        'sexual_orientation_id',
        'role_id',
        'born_date',
        'description',
        'role_id',
        'company_uid',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
    ];

    protected $dataCompleteValues = [
        'name',
        'surnames',
        'email',
        'password',
        'gender_id',
        'sexual_orientation_id',
        'role_id',
        'born_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function sexualOrientation(): BelongsTo
    {
        return $this->belongsTo(SexualOrientation::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_uid', 'uid');
    }

    public function userImages(): HasMany
    {
        return $this->hasMany(UserImage::class, 'user_uid', 'uid');
    }


    public function interactions(): HasMany
    {
        return $this->hasMany(TargetUsers::class, 'user_uid', 'uid');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'user_events', 'user_uid', 'event_uid')
            ->withPivot('likes', 'super_likes', 'logged_at');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_uid', 'uid')
            ->where('event_uid', $this->event->uid);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_uid', 'uid');
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->born_date)->age;
    }

    public function getEventAttribute()
    {
        $now = Carbon::now();
        return  $this->events()
            ->where('st_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->latest('logged_at')
            ->first() ?? $this->nextOrLastEvent();
    }

    public function getDataCompleteAttribute(): bool
    {
        foreach ($this->dataCompleteValues as $value) {
            if (!$this->{$value}) {
                return false;
                break;
            }
        }
        return true;
    }

    public function getDataImagesAttribute(): bool
    {
        return $this->userImages()->count() >= 1 ? true : false;
    }

    public function getLikesAttribute(): int
    {
        return $this->event?->pivot?->likes ?? 0;
    }

    public function getSuperLikesAttribute(): int
    {
        return $this->event?->pivot?->super_likes ?? 0;
    }

    public function scopeChats()
    {
        return Chat::where('event_uid', user()->event->uid)
            ->whereAny(['user1_uid', 'user2_uid'], user()->uid);
    }

    public function getUnreadChatsAttribute(): int
    {
        return ChatMessage::whereNot('sender_uid', $this->uid)
            ->where('read_at', false)
            ->whereHas('chat', function ($query) {
                $query->where('event_uid', $this->event->uid)
                    ->whereAny(['user1_uid', 'user2_uid'], $this->uid);
            })->count();
    }

    public function getCompleteRegisterAttribute(): bool
    {
        return $this->data_complete && $this->data_images
            ? true
            : false;
    }

    public function getIsPremiumAttribute(): bool
    {
        return $this->role_id == Role::PREMIUM ? true : false;
    }

    public function getMatchGenderAttribute()
    {
        switch ($this->sexual_orientation_id) {
            case SexualOrientation::BISEXUAL:
                return [Gender::MALE, Gender::FEMALE];
            case SexualOrientation::HETEROSEXUAL:
                return $this->gender_id == Gender::FEMALE ? [Gender::MALE] : [Gender::FEMALE];
            case SexualOrientation::HOMOSEXUAL:
                return [$this->gender_id];
        }
    }

    public static function whereTargetUsersFrom($auth)
    {
        return User::whereNot('uid', $auth->uid)
            ->has('userImages', '>=', 1)
            ->when(in_array($auth->sexual_orientation_id, [SexualOrientation::HOMOSEXUAL, SexualOrientation::HETEROSEXUAL]), function ($q) use ($auth) {
                $q->whereIn('gender_id', $auth->match_gender)
                    ->whereIn('sexual_orientation_id', [$auth->sexual_orientation_id, SexualOrientation::BISEXUAL]);
            })
            ->when($auth->sexual_orientation_id === SexualOrientation::BISEXUAL, function ($q) use ($auth) {
                $q->when($auth->gender_id === Gender::MALE, function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('gender_id', Gender::MALE)
                            ->whereIn('sexual_orientation_id', [SexualOrientation::HOMOSEXUAL, SexualOrientation::BISEXUAL]);
                    })->orWhere(function ($subQuery) {
                        $subQuery->where('gender_id', Gender::FEMALE)
                            ->whereIn('sexual_orientation_id', [SexualOrientation::HETEROSEXUAL, SexualOrientation::BISEXUAL]);
                    });
                });
                $q->when($auth->gender_id === Gender::FEMALE, function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('gender_id', Gender::FEMALE)
                            ->whereIn('sexual_orientation_id', [SexualOrientation::HOMOSEXUAL, SexualOrientation::BISEXUAL]);
                    })->orWhere(function ($subQuery) {
                        $subQuery->where('gender_id', Gender::MALE)
                            ->whereIn('sexual_orientation_id', [SexualOrientation::HETEROSEXUAL, SexualOrientation::BISEXUAL]);
                    });
                });
            })
            ->whereHas('events', function ($q) use ($auth) {
                $q->where('event_uid', $auth->event->uid);
            })
            ->whereNotIn('uid', function ($q) use ($auth) {
                $q->select('target_user_uid')
                    ->from('target_users as ui')
                    ->where('ui.user_uid', $auth->uid)
                    ->where('ui.event_uid', $auth->event->uid)
                    ->pluck('target_user_uid');
            });
    }

    public function scopeRemainingUsersToInteract()
    {
        return $this->interactions()->where('event_uid', $this->event->uid)->where('interaction_id', null)->get();
    }

    public function decrementInteraction(int $interaction): void
    {
        if ($interaction == Interaction::DISLIKE_ID) return;

        $name = match ($interaction) {
            Interaction::LIKE_ID => 'likes',
            Interaction::SUPER_LIKE_ID => 'super_likes',
        };

        $this->events()->updateExistingPivot($this->event->uid, [
            $name => max(0, $this->likes - 1)
        ]);
    }

    public function scopeGetNotificationsByType()
    {
        if (!$this->event) {
            return [];
        }

        $unread = $this->notifications()->where('read_at', null)->get()->groupBy('type_id');

        return [
            'like' => $unread->has(NotificationsType::LIKE_TYPE) ? $unread->get(NotificationsType::LIKE_TYPE)->count() : 0,
            'superlike' => $unread->has(NotificationsType::SUPER_LIKE_TYPE) ? $unread->get(NotificationsType::SUPER_LIKE_TYPE)->count() : 0,
            'hook' => $unread->has(NotificationsType::HOOK_TYPE) ? $unread->get(NotificationsType::HOOK_TYPE)->count() : 0,
            'message' => $this->unread_chats,
        ];
    }

    public function nextOrLastEvent()
    {
        $now = Carbon::now();

        if (!$this->company) {
            return null;
        }

        // Primero intentamos encontrar el evento futuro más cercano
        $futureEvent = $this->company->events()
            ->where('st_date', '>', $now)
            ->orderBy('st_date', 'asc')
            ->first();

        if ($futureEvent) {
            return $futureEvent;
        }

        // Si no hay eventos futuros, devolvemos el último evento pasado
        return $this->company->events()
            ->where('st_date', '<=', $now)
            ->orderBy('st_date', 'desc')
            ->first();
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'uid' => $this->uid,
            'event_uid' => $this->event->uid ?? null,
        ];
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
