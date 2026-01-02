<?php

declare(strict_types=1);

namespace App\Models;

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
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_uid', 'uid');
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

    public function images(): HasMany
    {
        return $this->hasMany(UserImage::class, 'user_uid', 'uid')->orderBy('order', 'asc');
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

    public function hooksAsUser1(): HasMany
    {
        return $this->hasMany(Hook::class, 'user1_uid', 'uid');
    }

    public function hooksAsUser2(): HasMany
    {
        return $this->hasMany(Hook::class, 'user2_uid', 'uid');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_uid', 'uid')
            ->where('read_at', false);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'user_uid', 'uid');
    }

    public function profilePicture(): HasOne
    {
        return $this->hasOne(UserImage::class, 'user_uid', 'uid')->ofMany('order', 'min');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(Settings::class, 'user_uid', 'uid');
    }

    public function age(): Attribute
    {
        return Attribute::get(fn () => $this->born_date->age);
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

    public function dataComplete(): Attribute
    {
        return Attribute::get(fn () => collect($this->dataCompleteValues)
            ->every(fn ($value) => ! empty($this->{$value}))
        );
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

    public function targetUsersCacheKey(): Attribute
    {
        return Attribute::get(fn () => 'target_users_uids_'.$this->uid.'_'.$this->event->uid);
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
            'settings',
        ])->loadCount([
            'hooksAsUser1',
            'hooksAsUser2',
            'events',
            'likesReceived',
        ]);
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
            'born_date' => 'date',
        ];
    }
}
