<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyPasswordResetToken extends Model
{
    protected $fillable = [
        'email',
        'token',
        'expires_at',
    ];

    protected $primaryKey = 'email';
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'email', 'email');
    }
}
