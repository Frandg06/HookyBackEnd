<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Stripe;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Services\StripeService;
use App\Actions\Customer\Stripe\PaymentAction;
use Illuminate\Container\Attributes\CurrentUser;

final class PaymentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, string $sessionId, PaymentAction $action, StripeService $stripeService)
    {
        $session = $stripeService->retrieveSession($sessionId);
        $action->execute($user, $session);

        return $this->successResponse('payment_intent_created');
    }
}
