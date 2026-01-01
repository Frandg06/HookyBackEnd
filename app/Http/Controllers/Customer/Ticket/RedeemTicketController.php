<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Ticket;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\Ticket\RedeemTicketAction;
use App\Http\Requests\Customer\Ticket\RedeemTicketRequest;

final class RedeemTicketController extends Controller
{
    /**
     * Handle the incoming request to redeem a ticket.
     */
    public function __invoke(#[CurrentUser] User $user, RedeemTicketRequest $request, RedeemTicketAction $action)
    {
        $code = $request->string('code')->toString();
        $action->execute($user, $code);

        return $this->successResponse('Ticket redeemed successfully');
    }
}
