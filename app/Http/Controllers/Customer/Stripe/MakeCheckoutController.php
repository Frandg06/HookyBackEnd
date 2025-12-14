<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Stripe\MakeCheckoutRequest;
use App\Http\Services\StripeService;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final class MakeCheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, MakeCheckoutRequest $request, StripeService $stripeService)
    {
        $price_id = $request->input('price_id');

        $checkoutSession = $stripeService->createPayment([
            'price_id' => $price_id,
            'customer_email' => $user->email,
        ]);

        return $this->successResponse('checkout_session_created', $checkoutSession->url, 201);
    }
}
