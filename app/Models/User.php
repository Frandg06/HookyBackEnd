<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use App\Models\Traits\HasVerified;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasUid, HasVerified;

    
    
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
        'verified',
        'event_uid',
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
        'description',
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
        return $this->hasMany(UsersInteraction::class);
    }

    public function events() : HasMany {
        return $this->hasMany(UserEvent::class, 'user_uid', 'uid');
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
}
