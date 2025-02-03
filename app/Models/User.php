<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUid;

    
    public const ROLE_USER = 2;
    public const ROLE_PREMIUM = 3;

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
        return $this->hasMany(UserImage::class);
    }

    public function interests() : HasMany {
        return $this->hasMany(UserInterest::class);
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

    public function getMatchGenderAttribute(): array {
        switch($this->sexual_orientation_id){
            case 1:
                return [1, 2];
            case 2:
                return $this->gender_id == 1 ? [2] : [1]; 
            case 3:
                return [$this->gender_id];
        }

    }

    public function interestBelongsToMany()
    {
        return $this->belongsToMany(Interest::class, 'user_interests', 'user_id', 'interest_id');
    }

    public function scopeGetUsersToInteract($query, $authUser, $usersWithInteraction, $usersWithoutInteraction) {
        return $query->whereIn("gender_id", $authUser->match_gender)
        ->where("sexual_orientation_id", $authUser->sexual_orientation_id)
        ->whereHas('events', function ($query) use ($authUser) {
          $query->where('event_uid', $authUser->event_uid);
        })
        ->whereNot('uid', $authUser->uid)
        ->whereNotIn('uid', $usersWithInteraction)
        ->whereRaw("
            EXISTS (
                SELECT 1 
                FROM user_interests 
                WHERE user_interests.user_id = users.id 
                GROUP BY user_interests.user_id 
                HAVING COUNT(user_interests.user_id) BETWEEN 3 AND 6
            )
        ")
        ->whereRaw("
            EXISTS (
                SELECT 1 
                FROM user_images 
                WHERE user_images.user_id = users.id 
                GROUP BY user_images.user_id 
                HAVING COUNT(user_images.user_id) = 3
            )
        ")
        ->orWhereIn('uid', $usersWithoutInteraction)
        ->limit(50)
        ->get();
    }

    public function scopeGetUserSuperLikes($query) {
        return $query->join('users_interactions as i1', 'users.uid', '=', 'i1.interaction_user_uid')
        ->where('i1.user_uid', $this->uid)
        ->where('i1.event_uid', $this->event_uid)
        ->whereExists(function ($query) {
            $query->from('users_interactions as i2')
                ->whereColumn('i2.user_uid', 'i1.interaction_user_uid')
                ->where('i2.interaction_user_uid', $this->uid)
                ->where('i2.event_uid', $this->event_uid)
                ->where('i2.interaction_id', Interaction::SUPER_LIKE_ID);
        })
        ->whereNotExists(function ($query) {
            $query->from('users_interactions as i3')
                  ->where('i3.user_uid', $this->uid)
                  ->whereColumn('i3.interaction_user_uid', 'i1.interaction_user_uid')
                  ->whereIn('i3.interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID]);
        })
        ->orderBy('i1.updated_at', 'desc')
        ->distinct()
        ->get(['users.*', 'i1.updated_at as ago']);
    }

    public function scopeGetUserLikes() {
        return self::query()
        ->join('users_interactions as i1', 'users.uid', '=', 'i1.interaction_user_uid')
        ->where('i1.user_uid', $this->uid)
        ->where('i1.event_uid', $this->event_uid)
        ->whereExists(function ($query) {
            $query->from('users_interactions as i2')
                ->whereColumn('i2.user_uid', 'i1.interaction_user_uid')
                ->where('i2.interaction_user_uid', $this->uid)
                ->where('i2.interaction_id', Interaction::LIKE_ID);
        })
        ->whereNotExists(function ($query) {
            $query->from('users_interactions as i3')
                  ->where('i3.user_uid', $this->uid)
                  ->whereColumn('i3.interaction_user_uid', 'i1.interaction_user_uid')
                  ->whereIn('i3.interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID]);
        })
        ->orderBy('i1.updated_at', 'desc')
        ->distinct()
        ->get(['users.*', 'i1.updated_at as ago']);
    }

    public function getUserHooks() {
        return self::query()
        ->join('users_interactions as i1', 'users.uid', '=', 'i1.interaction_user_uid')
        ->where('i1.user_uid', $this->uid)
        ->where('i1.event_uid', $this->event_uid)
        ->whereIn('i1.interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
        ->whereExists(function ($query) {
            $query->from('users_interactions as i2')
                ->whereColumn('i2.user_uid', 'i1.interaction_user_uid')
                ->where('i2.interaction_user_uid', $this->uid)
                ->where('i2.event_uid', $this->event_uid)
                ->whereIn('i2.interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID]);
        })
        ->orderBy('i1.updated_at', 'desc')
        ->distinct()
        ->get(['users.*', 'i1.updated_at as ago']);
    }

    public function refreshInteractions($interaction) {

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
        return [];
    }
}
