<?php

declare(strict_types=1);

namespace App\Actions\Customer\Ticket;

use App\Models\User;
use App\Models\Ticket;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Repositories\TicketRepository;
use App\Repositories\UserEventRepository;

final readonly class RedeemTicketAction
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private UserEventRepository $userEventRepository
    ) {}

    /**
     * Execute the action to redeem a ticket for a user.
     *
     * @return array{user_total: array{super_like_credits: int, like_credits: int}, ticket_add: array{super_likes: int, likes: int}}
     */
    public function execute(User $user, string $code): bool
    {
        return DB::transaction(function () use ($user, $code) {
            $event = $user->event;
            $ticket = $this->ticketRepository->findValidTicketByCodeAndEvent($code, $event->uid);

            throw_if(! $ticket, new ApiException('ticket_invalid', 404));

            $this->ticketRepository->markAsRedeemed($ticket, $user->uid, $event->timezone);
            $this->userEventRepository->addCredits(
                $user->uid,
                $event->uid,
                $ticket->likes,
                $ticket->super_likes
            );

            return true;
        });
    }
}
