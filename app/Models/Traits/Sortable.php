<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Http\Orders\QueryOrdenator;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeSort(Builder $builder, QueryOrdenator $ordenator)
    {
        $ordenator->apply($builder);
    }
}
