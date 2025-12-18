<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Stripe;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\Stripe\GetVipCheckoutAction;

final class MakeCheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, GetVipCheckoutAction $action)
    {
        $payment_url = $action->execute($user);

        return $this->successResponse('checkout_session_created', $payment_url, 201);
    }
}
