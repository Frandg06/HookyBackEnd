<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Stripe;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Stripe\PaymentAction;
use Illuminate\Container\Attributes\CurrentUser;

final class PaymentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, Request $request, PaymentAction $action)
    {
        $sessionId = $request->input('session_id');
        $action->execute($user, $sessionId);

        return $this->successResponse('payment_intent_created');
    }
}
