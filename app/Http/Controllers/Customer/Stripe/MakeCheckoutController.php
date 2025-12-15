<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Stripe;

use App\Actions\Customer\Stripe\GetPaymentLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Stripe\MakeCheckoutRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final class MakeCheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, MakeCheckoutRequest $request, GetPaymentLinkAction $action)
    {
        $price_id = $request->input('price_id');
        $payment_url = $action->execute($user, $price_id);

        return $this->successResponse('checkout_session_created', $payment_url, 201);
    }
}
