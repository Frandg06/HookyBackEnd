<?php

declare(strict_types=1);

namespace App\Http\Orders;

final class EventOrdenator extends QueryOrdenator
{
    public function name(string $value)
    {
        return $this->builder->orderBy('name', $value);
    }

    public function stDate(string $value)
    {
        return $this->builder->orderBy('st_date', $value);
    }

    public function endDate(string $value)
    {
        return $this->builder->orderBy('end_date', $value);
    }

    public function usersCount(string $value)
    {
        return $this->builder
            ->select('events.*')
            ->selectRaw('(SELECT COUNT(*) FROM user_events WHERE user_events.event_uid = events.uid) as user_count')
            ->orderBy('user_count', $value);
    }

    public function redeemedTickets(string $value)
    {
        return $this->builder
            ->select('events.*')
            ->selectRaw('(SELECT COUNT(*) FROM tickets WHERE tickets.event_uid = events.uid) as tickets_count')
            ->orderBy('tickets_count', $value);
    }
}
