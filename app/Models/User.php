<?php

namespace App\Models;

use App\Http\Resources\AuthUserResource;
use App\Models\Traits\Filterable;
use App\Models\Traits\HasUid;
use App\Models\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUid, Filterable, Sortable;

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
        return $this->belongsTo(Company::class);
    }

    public function userImages(): HasMany
    {
        return $this->hasMany(UserImage::class, 'user_uid', 'uid');
    }


    public function interactions(): HasMany
    {
        return $this->hasMany(UsersInteraction::class, 'user_uid', 'uid');
    }

    public function targetUsers(): HasMany
    {
        return $this->hasMany(UsersInteraction::class, 'interaction_user_uid', 'uid');
    }

    public function events(): HasMany
    {
        return $this->hasMany(UserEvent::class, 'user_uid', 'uid');
    }

    public function eventsBelongsToMany(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'user_events', 'user_uid', 'event_uid');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_uid', 'uid');
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
        return $this->eventsBelongsToMany()->orderByPivot('logged_at', 'desc')->first();
    }

    public function getCompanyUidAttribute(): string
    {
        return $this->event->company_uid;
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
        return $this->userImages()->count() == 3 ? true : false;
    }

    public function getLikesAttribute(): int
    {
        return $this->events()->where('event_uid', $this->event->uid)->first()->likes;
    }

    public function getSuperLikesAttribute(): int
    {
        return $this->events()->where('event_uid', $this->event->uid)->first()->super_likes;
    }

    public function getUnreadChatsAttribute(): int
    {
        return ChatMessage::whereNot('sender_uid', $this->uid)
            ->where('read_at', false)
            ->whereHas('chat', function ($query) {
                $query->where('event_uid', $this->event->uid)
                    ->where(function ($query2) {
                        $query2->where('user2_uid', $this->uid)
                            ->orWhere('user1_uid', $this->uid);
                    });
            })->count();
    }

    public function getCompleteRegisterAttribute(): bool
    {
        if ($this->data_complete && $this->data_images) {
            return true;
        }
        return false;
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

    public function scopeResource()
    {
        return AuthUserResource::make($this);
    }

    public static function whereTargetUsersFrom($auth)
    {
        return User::whereNot('uid', $auth->uid)
            ->has('userImages', '=', 3)
            ->when(in_array($auth->sexual_orientation_id, [SexualOrientation::HOMOSEXUAL, SexualOrientation::HETEROSEXUAL]), function ($q) use ($auth) {
                $q->whereIn('gender_id', $auth->match_gender)
                    ->whereIn('sexual_orientation_id', [$auth->sexual_orientation_id, SexualOrientation::BISEXUAL]);
            })
            ->when($auth->sexual_orientation_id === SexualOrientation::BISEXUAL,  function ($q) use ($auth) {
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
            ->whereDoesntHave('targetUsers', function ($q) use ($auth) {
                $q->where('user_uid', $auth->uid)
                    ->where('event_uid', $auth->event->uid);
            });
    }

    public function scopeRemainingUsersToInteract()
    {
        return $this->interactions()->where('event_uid', $this->event->uid)->where('interaction_id', null)->get();
    }

    public function refreshCredits($interaction)
    {
        $eventPivot = $this->events()->where('event_uid', $this->event->uid)->first();
        if ($interaction == Interaction::LIKE_ID) {
            $eventPivot->update([
                'likes' => $this->likes - 1
            ]);
        } elseif ($interaction == Interaction::SUPER_LIKE_ID) {
            $eventPivot->update([
                'super_likes' => $this->super_likes - 1
            ]);
        }
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
            'event_uid' => $this->event->uid
        ];
    }
}
