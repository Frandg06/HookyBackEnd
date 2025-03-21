<?php

namespace App\Http\Filters;

use App\Models\Gender;
use App\Models\Interaction;

class EventFilter extends QueryFilter
{
  public function range(string $value)
  {
    [$start, $end] = explode(',', $value);
    return $this->builder->where('st_date', '>=', $start)
      ->where('end_date', '<=', $end);
  }

  public function name(string $value)
  {
    return $this->builder->where('name', 'like', '%' . $value . '%');
  }

  public function stDateAfter(string $value)
  {
    return $this->builder->where('st_date', '>=', $value);
  }

  public function stDateBefore(string $value)
  {
    return $this->builder->where('st_date', '<=', $value);
  }

  public function peopleCountMin(string $value)
  {
    return $this->builder
      ->whereIn('events.uid', function ($query) use ($value) {
        $query->select('user_events.event_uid')
          ->from('user_events')
          ->groupBy('user_events.event_uid')
          ->havingRaw('COUNT(user_events.user_uid) >= ?', [$value]);
      });
  }

  public function peopleCountMax(string $value)
  {
    return $this->builder
      ->whereIn('events.uid', function ($query) use ($value) {
        $query->select('user_events.event_uid')
          ->from('user_events')
          ->groupBy('user_events.event_uid')
          ->havingRaw('COUNT(user_events.user_uid) <= ?', [$value]);
      });
  }

  public function ticketsMin(string $value)
  {
    return $this->builder
      ->whereIn('events.uid', function ($query) use ($value) {
        $query->select('tickets.event_uid')
          ->from('tickets')
          ->groupBy('tickets.event_uid')
          ->havingRaw('COUNT(tickets.redeemed) >= ?', [$value]);
      });
  }

  public function ticketsMax(string $value)
  {
    return $this->builder
      ->whereIn('events.uid', function ($query) use ($value) {
        $query->select('tickets.event_uid')
          ->from('tickets')
          ->groupBy('tickets.event_uid')
          ->havingRaw('COUNT(tickets.redeemed) <= ?', [$value]);
      });
  }

  public function earningsMin(string $value)
  {
    return $this->builder
      ->whereIn('events.uid', function ($query) use ($value) {
        $query->select('tickets.event_uid')
          ->from('tickets')
          ->groupBy('tickets.event_uid')
          ->havingRaw('SUM(CASE WHEN tickets.redeemed = TRUE THEN tickets.price ELSE 0 END) >= ?', [$value]);
      });
  }

  public function earningsMax(string $value)
  {
    return $this->builder
      ->whereIn('events.uid', function ($query) use ($value) {
        $query->select('tickets.event_uid')
          ->from('tickets')
          ->groupBy('tickets.event_uid')
          ->havingRaw('SUM(CASE WHEN tickets.redeemed = TRUE THEN tickets.price ELSE 0 END) <= ?', [$value]);
      });
  }

  public function hooksMin(string $value)
  {
    return $this->builder
      ->whereIn('events.uid', function ($query) use ($value) {
        $query->select('user_events.event_uid')
          ->from('user_events')
          ->groupBy('user_events.event_uid')
          ->havingRaw('COUNT(user_events.user_uid) >= ?', [$value]);
      });
  }

  public function hooksMax(string $value)
  {
    return $this->builder
      ->whereIn('events.uid', function ($query) use ($value) {
        $query->select('users_interactions.event_uid')
          ->from('users_interactions')
          ->groupBy('users_interactions.event_uid')
          ->havingRaw('COUNT(users_interactions.interaction_id) <= ?', [$value]);
      });
  }

  public function percentages(string $value)
  {

    $gender = $value == 'males' ? Gender::MALE : Gender::FEMALE;
    $genderToCompare = $value == 'males' ? Gender::FEMALE : Gender::MALE;

    return $this->builder
      ->whereIn('events.uid', function ($query) use ($gender, $genderToCompare) {
        $query->select('user_events.event_uid')
          ->from('user_events')
          ->join('users', 'users.uid', '=', 'user_events.user_uid') // Relación con users
          ->groupBy('user_events.event_uid')
          ->havingRaw(
            'SUM(CASE WHEN users.gender_id = ? THEN 1 ELSE 0 END) > SUM(CASE WHEN users.gender_id = ? THEN 1 ELSE 0 END)',
            [$gender, $genderToCompare]
          );
      });
  }
}
