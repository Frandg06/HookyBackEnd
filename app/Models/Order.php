<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'uuid',
        'order_number',
        'session_id',
        'user_uid',
        'company_uid',
        'product_uuid',
    ];

    protected $primaryKey = 'uuid';

    public static function booted()
    {
        self::creating(function ($model) {
            $latestOrder = self::latest()->value('order_number');

            if (! $latestOrder) {
                return $model->order_number = 'ODR-78900';
            }

            [$string, $number] = explode('-', $latestOrder);
            $model->order_number = $string.'-'.((int) $number + 1);
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_uuid', 'uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'related_uid', 'uuid');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'related_uid', 'uuid');
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
