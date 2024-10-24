<?php

namespace App\Models;

use App\Models\Traits\HasUid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserImage extends Model
{
    use HasFactory, HasUid;

    protected $fillable = [
        'url',
        'height',
        'width',
        'order',
        'size',
        'extension'
    ];

    protected $visible = [
        'uid',
        'order',
        'extension',
        'url',
    ];
    protected $hidden = [
        'id',
        'user_id',
        'height',
        'width',
        'size',
        'created_at',
        'updated_at',
    ];

    public function getUrlAttribute() : string {
        return "hooky/profile/" . $this->user->uid ."/".$this->uid . "." . $this->extension;
    }

    public function getWebUrlAttribute() : string {
        return config("filesystems.disks.r2.url") . "profile/" . $this->user->uid ."/".$this->uid . "." . $this->extension;
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }


}
