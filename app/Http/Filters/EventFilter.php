<?php

namespace App\Http\Filters;


class EventFilter extends QueryFilter
{
    public function range(string $value) {
      [$start, $end] = explode(',', $value);
      return $this->builder->where('st_date', '>=', $start)
              ->where('end_date', '<=', $end);
    }

}