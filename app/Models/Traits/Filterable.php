<?php

namespace App\Models\Traits;

use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;


trait Filterable
{
    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }
}
