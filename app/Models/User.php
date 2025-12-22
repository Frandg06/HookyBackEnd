<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use App\Enums\SocialProviders;
use App\Enums\User\GenderEnum;
use App\Models\Traits\Sortable;
use App\Models\Traits\Filterable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Enums\User\SexualOrientationEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\User
 *
 * @property string $uid
 * @property string $name
 * @property string $surnames
 * @property string $email
 * @property string $password
 * @property int $gender_id
 * @property int $sexual_orientation_id
 * @property int $role_id
 * @property string|null $born_date
 * @property string|null $description
 * @property string|null $company_uid
 * @property SocialProviders|null $provider_name
 * @property string|null $provider_id
 * @property bool $auto_password
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Company|null $company
 * @property-read int $age
 * @property-read bool $complete_register
 * @property-read bool $data_complete
 * @property-read bool $data_images
 * @property-read int $is_premium
 * @property-read int $likes
 * @property-read int $super_likes
 * @property-read bool $auto_password
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Event> $events
 * @property-read \Illuminate\Database\Eloquent\Collection<int, UserImage> $images
 * @property-read int|null $user_images_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAny(array $columns, mixed $   value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNot(string $column, mixed $value)
 */
final class User extends Authenticatable implements JWTSubject
{
    use Filterable;
    use HasFactory;
    use HasUuids;
    use Notifiable;
    use Sortable;

    public $incrementing = false;

    protected $table = 'users';

    protected $primaryKey = 'uid';

    protected $keyType = 'string';

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
        'gender',
        'sexual_orientation_id',
        'sexual_orientation',
        'role_id',
        'born_date',
        'description',
        'company_uid',
        'provider_name',
        'provider_id',
        'auto_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
    ];

    protected $dataCompleteValues = [
        'name',
        'email',
        'password',
        'gender',
        'sexual_orientation',
        'role_id',
        'born_date',
    ];

    public static function whereTargetUsersFrom($auth)
    {
        return self::whereNot('uid', $auth->uid)
            ->has('images', '>', 0)
            ->when(in_array($auth->sexual_orientation_id, [SexualOrientation::HOMOSEXUAL, SexualOrientation::HETEROSEXUAL]), function ($q) use ($auth) {
                $q->whereIn('gender_id', $auth->match_gender)
                    ->whereIn('sexual_orientation_id', [$auth->sexual_orientation_id, SexualOrientation::BISEXUAL]);
            })
            ->when($auth->sexual_orientation_id === SexualOrientation::BISEXUAL, function ($q) use ($auth) {
                $q->when($auth->gender_id === Gender::MALE, function ($query) {
                    $query->where(function ($q) {
                        $q->where(function ($subQuery) {
                            $subQuery->where('gender_id', Gender::MALE)
                                ->whereIn('sexual_orientation_id', [SexualOrientation::HOMOSEXUAL, SexualOrientation::BISEXUAL]);
                        })->orWhere(function ($subQuery) {
                            $subQuery->where('gender_id', Gender::FEMALE)
                                ->whereIn('sexual_orientation_id', [SexualOrientation::HETEROSEXUAL, SexualOrientation::BISEXUAL]);
                        });
                    });
                });
                $q->when($auth->gender_id === Gender::FEMALE, function ($query) {
                    $query->where(function ($q) {
                        $q->where(function ($subQuery) {
                            $subQuery->where('gender_id', Gender::FEMALE)
                                ->whereIn('sexual_orientation_id', [SexualOrientation::HOMOSEXUAL, SexualOrientation::BISEXUAL]);
                        })->orWhere(function ($subQuery) {
                            $subQuery->where('gender_id', Gender::MALE)
                                ->whereIn('sexual_orientation_id', [SexualOrientation::HETEROSEXUAL, SexualOrientation::BISEXUAL]);
                        });
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
                    ->where('ui.event_uid', $auth->event->uid);
            });
    }

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

    public function images(): HasMany
    {
        return $this->hasMany(UserImage::class, 'user_uid', 'uid')->orderBy('order', 'asc');
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

    public function activeEvent(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'user_events', 'user_uid', 'event_uid')
            ->withPivot('likes', 'super_likes', 'logged_at')
            ->where('st_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest('logged_at')
            ->limit(1);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_uid', 'uid')
            ->where('event_uid', $this->activeEvent?->first()?->uid ?? null);
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
        return $this->activeEvent->first();
    }

    public function getDataCompleteAttribute(): bool
    {
        foreach ($this->dataCompleteValues as $value) {
            if (! $this->{$value}) {
                return false;
                break;
            }
        }

        return true;
    }

    public function getDataImagesAttribute(): bool
    {
        return $this->images->count() >= 1 ? true : false;
    }

    public function getLikesAttribute(): int
    {
        return $this->activeEvent->first()?->pivot->likes ?? 0;
    }

    public function getSuperLikesAttribute(): int
    {
        return $this->activeEvent->first()?->pivot->super_likes ?? 0;
    }

    public function scopeChats()
    {
        return Chat::where('event_uid', user()->event?->uid)
            ->whereAny(['user1_uid', 'user2_uid'], user()->uid);
    }

    public function getUnreadChatsAttribute(): int
    {
        return ChatMessage::whereNot('sender_uid', $this->uid)
            ->where('read_at', false)
            ->whereHas('chat', function ($query) {
                $query->where('event_uid', $this->event?->uid)
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
        return $this->role_id === Role::PREMIUM ? true : false;
    }

    public function getMatchGenderAttribute()
    {
        switch ($this->sexual_orientation_id) {
            case SexualOrientation::BISEXUAL:
                return [Gender::MALE, Gender::FEMALE];
            case SexualOrientation::HETEROSEXUAL:
                return $this->gender_id === Gender::FEMALE ? [Gender::MALE] : [Gender::FEMALE];
            case SexualOrientation::HOMOSEXUAL:
                return [$this->gender_id];
        }
    }

    public function scopeRemainingUsersToInteract()
    {
        return $this->interactions()->where('event_uid', $this->event?->uid)->where('interaction_id', null)->get();
    }

    public function decrementInteraction(int $interaction): void
    {
        if ($interaction === Interaction::DISLIKE_ID || $this->isPremium()) {
            return;
        }

        $name = match ($interaction) {
            Interaction::LIKE_ID => 'likes',
            Interaction::SUPER_LIKE_ID => 'super_likes',
        };

        $this->activeEvent->first()?->pivot->decrement($name);
    }

    public function scopeGetNotificationsByType()
    {
        if (! $this->event) {
            return [
                'like' => 0,
                'superlike' => 0,
                'hook' => 0,
                'message' => 0,
            ];
        }

        $unread = $this->notifications->where('read_at', null)->groupBy('type_id');

        return [
            'like' => $unread->has(NotificationsType::LIKE_TYPE) ? $unread->get(NotificationsType::LIKE_TYPE)->count() : 0,
            'superlike' => $unread->has(NotificationsType::SUPER_LIKE_TYPE) ? $unread->get(NotificationsType::SUPER_LIKE_TYPE)->count() : 0,
            'hook' => $unread->has(NotificationsType::HOOK_TYPE) ? $unread->get(NotificationsType::HOOK_TYPE)->count() : 0,
            'message' => $this->unread_chats,
        ];
    }

    public function scopeLoadRelations(): self
    {
        return $this->load([
            'images',
            'activeEvent',
            'notifications',
            'company',
        ]);
    }

    public function scopeIsPremium()
    {
        return $this->role_id === Role::PREMIUM;
    }

    public function nextOrLastEvent()
    {
        $now = Carbon::now();

        if (! $this->company) {
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
        ];
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
            'auto_password' => 'boolean',
            'provider_name' => SocialProviders::class,
            'sexual_orientation' => SexualOrientationEnum::class,
            'gender' => GenderEnum::class,
        ];
    }
}
