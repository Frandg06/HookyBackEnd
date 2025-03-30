<?php

namespace App\Http\Filters;

use App\Models\Gender;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class UserFilter extends QueryFilter
{
  public function name(string $value)
  {
    return $this->builder->whereRaw("LOWER(name || ' ' || surnames) LIKE ?", ['%' . strtolower($value) . '%']);
  }

  public function ageMin(int $age)
  {
    $date = now()->subYears($age)->format('Y-m-d');
    return $this->builder->whereDate('born_date', '<=', $date);
  }

  public function ageMax(int $age)
  {
    $date = now()->subYears($age)->format('Y-m-d');
    return $this->builder->where('born_date', '>=', $date);
  }

  public function gender(string $gender)
  {
    $gederCode = $gender == "male" ? Gender::MALE : Gender::FEMALE;
    return $this->builder->where('gender_id', $gederCode);
  }

  public function email(string $email)
  {
    return $this->builder->where('email', 'like', '%' . $email . '%');
  }

  public function socials(string $name)
  {
    return $this->builder->where(function ($query) use ($name) {
      $query->where('tw', 'like', '%' . $name . '%')
        ->orWhere('ig', 'like', '%' . $name . '%');
    });
  }

  public function role(string $role)
  {
    $roleCode = $role == "user" ? Role::USER : Role::PREMIUM;

    return $this->builder->where('role_id', $roleCode);
  }

  // public function consuptionMin(int $value)
  // {
  //   return $this->builder->whereHas('tickets', function ($query) use ($value) {
  //     $query->where('redeemed', true)
  //       ->groupBy('user_uid', 'tickets.id')
  //       ->havingRaw('SUM(price) >= ?', [$value]);
  //   });
  // }

  // public function consuptionMax(int $value)
  // {
  //   return $this->builder->whereHas('tickets', function ($query) use ($value) {
  //     $query->where('redeemed', true)
  //       ->groupBy('user_uid', 'tickets.id')
  //       ->havingRaw('SUM(price) <= ?', [$value]);
  //   });
  // }
}
