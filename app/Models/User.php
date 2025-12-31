<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use App\Enums\InteractionEnum;
use App\Enums\SocialProviders;
use App\Enums\User\GenderEnum;
use App\Models\Traits\Sortable;
use App\Models\Traits\Filterable;
use App\Enums\NotificationTypeEnum;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Enums\User\SexualOrientationEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'gender',
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

    protected array $dataCompleteValues = [
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
            ->when($auth->sexual_orientation->isHomosexual(), function ($q) use ($auth) {
                $q->where('gender', $auth->gender->same())
                    ->whereIn('sexual_orientation', [$auth->sexual_orientation, SexualOrientationEnum::BISEXUAL]);
            })
            ->when($auth->sexual_orientation->isHeterosexual(), function ($q) use ($auth) {
                $q->where('gender', $auth->gender->opposite())
                    ->whereIn('sexual_orientation', [$auth->sexual_orientation, SexualOrientationEnum::BISEXUAL]);
            })
            ->when($auth->sexual_orientation->isBisexual(), function ($q) use ($auth) {
                $q->when($auth->gender->isMale(), function ($query) {
                    $query->where(function ($q) {
                        $q->where(function ($subQuery) {
                            $subQuery->where('gender', GenderEnum::MALE)
                                ->whereIn('sexual_orientation', [SexualOrientationEnum::GAY, SexualOrientationEnum::BISEXUAL]);
                        })->orWhere(function ($subQuery) {
                            $subQuery->where('gender', GenderEnum::FEMALE)
                                ->whereIn('sexual_orientation', [SexualOrientationEnum::HETEROSEXUAL, SexualOrientationEnum::BISEXUAL]);
                        });
                    });
                });
                $q->when($auth->gender->isFemale(), function ($query) {
                    $query->where(function ($q) {
                        $q->where(function ($subQuery) {
                            $subQuery->where('gender', GenderEnum::FEMALE)
                                ->whereIn('sexual_orientation', [SexualOrientationEnum::LESBIAN, SexualOrientationEnum::BISEXUAL]);
                        })->orWhere(function ($subQuery) {
                            $subQuery->where('gender', GenderEnum::MALE)
                                ->whereIn('sexual_orientation', [SexualOrientationEnum::HETEROSEXUAL, SexualOrientationEnum::BISEXUAL]);
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

    public function profilePicture(): HasMany
    {
        return $this->hasMany(UserImage::class, 'user_uid', 'uid')->orderBy('order', 'asc')->limit(1);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(TargetUsers::class, 'user_uid', 'uid');
    }

    public function likesReceived(): HasMany
    {
        return $this->hasMany(TargetUsers::class, 'target_user_uid', 'uid')
            ->whereIn('interaction', [InteractionEnum::LIKE, InteractionEnum::SUPERLIKE]);
    }

    public function hooksAsUser1()
    {
        return $this->hasMany(Hook::class, 'user1_uid', 'uid');
    }

    public function hooksAsUser2()
    {
        return $this->hasMany(Hook::class, 'user2_uid', 'uid');
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
            ->where('read_at', false);
    }

    public function likeNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_uid', 'uid')
            ->whereIn('type_id', [NotificationsType::LIKE_TYPE, NotificationsType::SUPER_LIKE_TYPE]);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_uid', 'uid');
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->born_date)->age;
    }

    public function getEventAttribute(): ?Event
    {
        return $this->activeEvent->first();
    }

    public function hooks(): Attribute
    {
        return Attribute::get(function () {
            return $this->hooksAsUser1->merge($this->hooksAsUser2);
        });
    }

    public function hooksCount(): Attribute
    {
        return Attribute::get(function () {
            return $this->hooks->count();
        });
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

    public function getDataImagesAttribute(): Attribute
    {
        return Attribute::get(fn () => $this->images->count() >= 1);
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

    public function completeRegister(): Attribute
    {
        return Attribute::get(fn () => $this->data_complete && $this->data_images);
    }

    public function isPremium(): Attribute
    {
        return Attribute::get(fn () => $this->role_id === Role::PREMIUM);
    }

    public function unreadNotifications(): Attribute
    {
        $unread_notifications = $this->notifications->where('event_uid', $this->event?->uid)->groupBy('type');

        return Attribute::get(function () use ($unread_notifications) {
            return [
                'like' => $unread_notifications->get(NotificationTypeEnum::LIKE->value)?->count() ?? 0,
                'superlike' => $unread_notifications->get(NotificationTypeEnum::SUPERLIKE->value)?->count() ?? 0,
                'hook' => $unread_notifications->get(NotificationTypeEnum::HOOK->value)?->count() ?? 0,
                'message' => $this->unread_chats,
            ];
        });
    }

    public function scopeLoadRelations(): self
    {
        return $this->load([
            'images',
            'activeEvent',
            'company',
        ])->loadCount([
            'hooksAsUser1',
            'hooksAsUser2',
            'events',
            'likesReceived',
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
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
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
