<?php

namespace App\Models;

use App\Http\Resources\AuthUserResource;
use App\Models\Traits\HasUid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUid;

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
        'sexual_orientation_id',
        'role_id',
        'city',
        'born_date',
        'description',
        'like_credits',
        'super_like_credits',
        'tw',
        'ig',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'role_id',
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
        'city',
        'born_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    public function gender() : BelongsTo {
        return $this->belongsTo(Gender::class);
    }

    public function sexualOrientation() : BelongsTo {
        return $this->belongsTo(SexualOrientation::class);
    }

    public function role() : BelongsTo {
        return $this->belongsTo(Role::class);
    }

    public function company() : BelongsTo {
        return $this->belongsTo(Company::class);
    }

    public function userImages() : HasMany {
        return $this->hasMany(UserImage::class, 'user_uid', 'uid');
    }

    public function interests() : HasMany {
        return $this->hasMany(UserInterest::class, 'user_uid', 'uid');
    }

    public function interactions() : HasMany {
        return $this->hasMany(UsersInteraction::class, 'user_uid', 'uid');
    }

    public function events() : HasMany {
        return $this->hasMany(UserEvent::class, 'user_uid', 'uid');
    }

    public function notifications(): HasMany {
        return $this->hasMany(Notification::class, 'user_uid', 'uid');
    }

    public function getAgeAttribute() : int {
        return Carbon::parse($this->born_date)->age;
    }

    public function getLikeCreditsAttribute() : int {
        return $this->events()->activeEventData()->likes;
    }
    public function getSuperLikeCreditsAttribute() : int {
        return $this->events()->activeEventData()->super_likes;
    }

    public function getEventUidAttribute() : string {
        return $this->events()->activeEventData()->event_uid;
    }

    public function getCompanyUidAttribute() : string {
        return $this->events()->getCompanyByEvent();
    }

    public function getAuthEventAttribute()  {
        return $this->events()->activeEventData();
    }


    public function getDataCompleteAttribute() : bool {
        foreach ($this->dataCompleteValues as $value) {
            if(!$this->{$value}) {
                return false; 
                break;
            }
        }
        return true; 
    }

    public function getDataImagesAttribute() : bool {
        return $this->userImages()->count() == 3 ? true : false;
    }

    public function getDataInterestAttribute() : bool {
        return $this->interests()->count() >= 3 ? true : false;
    }

    public function getCompleteRegisterAttribute() : bool {
        if($this->data_complete && $this->data_images && $this->data_interest) {
            return true;
        }
        return false;
    }
    public function getIsPremiumAttribute() : bool {
        return $this->role_id == Role::PREMIUM ? true : false;
    }

    public function getMatchGenderAttribute(): array {
        switch($this->sexual_orientation_id){
            case SexualOrientation::BISEXUAL:
                return [Gender::MALE, Gender::FEMALE];
            case SexualOrientation::HETEROSEXUAL:
                return $this->gender_id == Gender::FEMALE ? [Gender::MALE] : [Gender::FEMALE];
            case SexualOrientation::HOMOSEXUAL:
                return [$this->gender_id];
        }
    }

    public function scopeResource() {
        return AuthUserResource::make($this);
    }

    public function interestBelongsToMany()
    {
        return $this->belongsToMany(Interest::class, 'user_interests', 'user_uid', 'interest_id');
    }

    public function scopeGetUsersToInteract($query, $authUser, $usersWithInteraction, $usersWithoutInteraction) 
    {
        return $query->whereIn("gender_id", $authUser->match_gender)
        ->whereIn("sexual_orientation_id", [$authUser->sexual_orientation_id, SexualOrientation::BISEXUAL])
        ->whereHas('events', function ($query) use ($authUser) {
          $query->where('event_uid', $authUser->event_uid);
        })->eligibleUsers($authUser, $usersWithInteraction, $usersWithoutInteraction);
        
    }

    public function scopeGetBisexualUsersToInteract($query, $authUser, $usersWithInteraction, $usersWithoutInteraction) 
    {
        return $query->where(function ($q) use ($authUser) {
                if ($authUser->gender_id === Gender::MALE) {
                    $q->where(function ($subQuery) {
                        $subQuery->where('gender_id', Gender::MALE)
                                 ->whereIn('sexual_orientation_id', [SexualOrientation::HOMOSEXUAL, SexualOrientation::BISEXUAL]);
                    })->orWhere(function ($subQuery) {
                        $subQuery->where('gender_id', Gender::FEMALE)
                                 ->whereIn('sexual_orientation_id', [SexualOrientation::HETEROSEXUAL, SexualOrientation::BISEXUAL]);
                    });
                } elseif ($authUser->gender_id === Gender::FEMALE) {
                    $q->where(function ($subQuery) {
                        $subQuery->where('gender_id', Gender::FEMALE)
                                 ->whereIn('sexual_orientation_id', [SexualOrientation::HOMOSEXUAL, SexualOrientation::BISEXUAL]);
                    })->orWhere(function ($subQuery) {
                        $subQuery->where('gender_id', Gender::MALE)
                                 ->whereIn('sexual_orientation_id', [SexualOrientation::HETEROSEXUAL, SexualOrientation::BISEXUAL]);
                    });
                }
            })
            ->eligibleUsers($authUser, $usersWithInteraction, $usersWithoutInteraction);
    }

    public function scopeEligibleUsers($query, $authUser, $usersWithInteraction, $usersWithoutInteraction) 
    {
        return $query->whereNot('uid', $authUser->uid)
        ->whereNotIn('uid', $usersWithInteraction)
        ->whereRaw("
            EXISTS (
                SELECT 1 
                FROM user_interests 
                WHERE user_interests.user_uid = users.uid 
                GROUP BY user_interests.user_uid 
                HAVING COUNT(user_interests.user_uid) BETWEEN 3 AND 6
            )
        ")
        ->whereRaw("
            EXISTS (
                SELECT 1 
                FROM user_images 
                WHERE user_images.user_uid = users.uid 
                GROUP BY user_images.user_uid 
                HAVING COUNT(user_images.user_uid) = 3
            )
        ")
        ->orWhereIn('uid', $usersWithoutInteraction)
        ->orderBy('created_at', 'asc')
        ->orderBy('id', 'asc')
        ->limit(50)
        ->get();
    }

    public function scopeRemainingUsersToInteract() {
        return $this->interactions()->where('event_uid', $this->event_uid)->where('interaction_id', null)->get();
    }
    
    public function refreshInteractions($interaction) 
    {
        if($interaction == Interaction::LIKE_ID) {
            $this->events()->activeEventData()->update([
                'likes' => $this->like_credits - 1
            ]);
        }elseif($interaction == Interaction::SUPER_LIKE_ID) {
            $this->events()->activeEventData()->update([
                'super_likes' => $this->super_like_credits - 1
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
            'event_uid' => $this->event_uid
        ];
    }
}
