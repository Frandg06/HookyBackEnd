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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
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

    public function getAgeAttribute() : int {
        return Carbon::parse($this->born_date)->age;
    }

    

    public function getDataCompleteAttribute() : bool {
        foreach ($this->getFillable() as $value) {
            if(!$this->{$value} && $value != 'ig' && $value != 'tw' && $value != 'data_images') {
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

    public function interestBelongsToMany()
    {
        return $this->belongsToMany(Interest::class, 'user_interests', 'user_id', 'interest_id');
    }

    public function prevPage(LengthAwarePaginator $paginator)
    {
        return ($paginator->currentPage() - 1) < 1 ? null : $paginator->currentPage() - 1;
    }

    public function nextPage(LengthAwarePaginator $paginator)
    {
        return ($paginator->currentPage() + 1) > $paginator->lastPage() ? null : $paginator->currentPage() + 1;
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
