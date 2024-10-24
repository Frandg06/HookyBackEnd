<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasUid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasUid;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'role_id',
        'updated_at',
        'created_at',
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

    public function socials() : BelongsToMany {
        return $this->belongsToMany(Social::class, 'user_socials', 'user_id', 'social_id')->withPivot('social_name');
    }

    public function getAgeAttribute() : int {
        return Carbon::parse($this->born_date)->age;
    }

    public function getInstagramAttribute() {
        $social = $this->socials()->where("social_id", 1)->first();

        return $social->base_url . $social->pivot->social_name;
    }

    public function getTwAttribute() {
        $social = $this->socials()->where("social_id", 2)->first();

        return $social->base_url . $social->pivot->social_name;
    }

    public function completeInfo(array $array) {
        $this->socials()->attach($array['socials']);
        return $this->update($array);
    }

    public function setCompleteData(bool $value) {
        $this->data_complete = $value;
        $this->save();
    }

    public function isDataComplete() {
        foreach ($this->getFillable() as $key => $value) {
            if(!$value) {
                return $this->setCompleteData(false);
            }
        }

        if($this->userImages()->count() < 3) {
            return $this->setCompleteData(false);
        }

        return $this->setCompleteData(true);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
