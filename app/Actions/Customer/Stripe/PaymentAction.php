<?php

declare(strict_types=1);

namespace App\Actions\Customer\Stripe;

use App\Exceptions\ApiException;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;

final readonly class PaymentAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, Session $session): void
    {
        DB::transaction(function () use ($user, $session) {
            if ($session->payment_status !== 'paid') {
                throw new ApiException('payment_not_completed', 422);
            }
            $user->vipPayments()->create([
                'stripe_payment_id' => $session->id,
            ]);

            $user->update(['role_id' => Role::PREMIUM]);
        });
    }
}
