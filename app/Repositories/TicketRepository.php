<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Ticket;

final class TicketRepository
{
    public function findValidTicketByCodeAndEvent(string $code, string $eventUid): ?Ticket
    {
        return Ticket::where('code', $code)
            ->where('event_uid', $eventUid)
            ->where('redeemed', false)
            ->first();
    }

    public function markAsRedeemed(Ticket $ticket, string $userUid, string $timezone): void
    {
        $ticket->update([
            'user_uid' => $userUid,
            'redeemed' => true,
            'redeemed_at' => now($timezone),
        ]);
    }
}
