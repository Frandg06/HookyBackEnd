<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInterest extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id', 'interest_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function interest(): BelongsTo
    {
        return $this->belongsTo(Interest::class);
    }
}
