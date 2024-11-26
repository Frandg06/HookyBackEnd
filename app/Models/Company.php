<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Company extends Model
{
    use HasFactory, HasApiTokens, HasUid;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'password',
    ];

    public function events() : HasMany {
        return $this->hasMany(Event::class, 'company_id');
    }

    public function getlinkAttribute() {
        return config("app.front_url") . "/event?uid=" . $this->uid;
    }

    public function timezone() : BelongsTo {
        return $this->belongsTo(TimeZone::class, 'timezone_uid', 'uid');
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
