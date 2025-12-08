<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CompanyPasswordResetToken extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'expires_at',
    ];

    protected $primaryKey = 'email';

    protected $keyType = 'string';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'email', 'email');
    }
}
