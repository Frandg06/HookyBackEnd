<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
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
        'timezone_uid',
        'average_ticket_price',
    ];

    public function events() : HasMany {
        return $this->hasMany(Event::class, 'company_uid', 'uid');
    }
    
    public function tickets() : HasMany {
        return $this->hasMany(Ticket::class, 'company_uid', 'uid');
    }

    public function getlinkAttribute() {
        return config("app.front_url") . "/event?uid=" . $this->uid;
    }

    public function timezone() : BelongsTo {
        return $this->belongsTo(TimeZone::class, 'timezone_uid', 'uid');
    }

    public function pricing_plan() : BelongsTo {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_uid', 'uid');
    }

    public function checkEventLimit() {
        $events = $this->events()->nextMontEvents()->count();
        $limit = $this->pricing_plan->limit_events;
        return $events < $limit;
    }

    public function checkEventInSameDay($date) {
        $events = $this->events()->eventInSameDay($date)->count();
        return $events > 0;
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
