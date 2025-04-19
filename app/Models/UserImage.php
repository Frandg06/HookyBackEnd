<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserImage extends Model
{
    use HasUuids;

    protected $fillable = [
        'url',
        'order',
        'size',
        'type',
        'name',
    ];

    protected $visible = [
        'uid',
        'order',
        'url',
        'size',
        'type',
        'name',
        'web_url',
    ];

    protected $appends = [
        'web_url',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function getUrlAttribute(): string
    {
        return 'hooky/profile/'.$this->user->uid.'/'.$this->uid.config('filesystems.disks.r2.image_default_extension');
    }

    public function getWebUrlAttribute(): string
    {
        return config('filesystems.disks.r2.url').'profile/'.$this->user->uid.'/'.$this->uid.config('filesystems.disks.r2.image_default_extension');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function uniqueIds(): array
    {
        return ['uid'];
    }
}
