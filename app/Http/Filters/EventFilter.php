<?php

declare(strict_types=1);

namespace App\Http\Filters;

use App\Models\Gender;

final class EventFilter extends QueryFilter
{
    public function range(string $value)
    {
        [$start, $end] = explode(',', $value);

        return $this->builder->where('st_date', '>=', $start)
            ->where('end_date', '<=', $end);
    }

    public function name(string $value)
    {
        return $this->builder->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($value).'%']);
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

    public function percentages(string $value)
    {

        $gender = $value === 'males' ? Gender::MALE : Gender::FEMALE;
        $genderToCompare = $value === 'males' ? Gender::FEMALE : Gender::MALE;

        return $this->builder
            ->whereIn('events.uid', function ($query) use ($gender, $genderToCompare) {
                $query->select('user_events.event_uid')
                    ->from('user_events')
                    ->join('users', 'users.uid', '=', 'user_events.user_uid')
                    ->groupBy('user_events.event_uid')
                    ->havingRaw(
                        'SUM(CASE WHEN users.gender_id = ? THEN 1 ELSE 0 END) > SUM(CASE WHEN users.gender_id = ? THEN 1 ELSE 0 END)',
                        [$gender, $genderToCompare]
                    );
            });
    }

    public function city(string $value)
    {
        return $this->builder->whereRaw('LOWER(city) LIKE ?', ['%'.mb_strtolower($value).'%']);
    }

    public function company(string $value)
    {
        return $this->builder->where('company_uid', $value);
    }
}
