<?php

namespace App\Http\Filters;

use App\Http\Filters\QueryFilter;

class TicketFilter extends QueryFilter
{
  public function name($builder, $value)
  {
    return $builder->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($value) . '%']);
  }

  public function priceMin($builder, $value)
  {
    return $builder->where('price', '>=', $value);
  }

  public function priceMax($builder, $value)
  {
    return $builder->where('price', '<=', $value);
  }

  public function likesMin($builder, $value)
  {
    return $builder->where('likes', '>=', $value);
  }

  public function likesMax($builder, $value)
  {
    return $builder->where('likes', '<=', $value);
  }

  public function superlikesMin($builder, $value)
  {
    return $builder->where('super_likes', '>=', $value);
  }

  public function superlikesMax($builder, $value)
  {
    return $builder->where('super_likes', '<=', $value);
  }
}
