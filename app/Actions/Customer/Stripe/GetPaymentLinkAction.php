<?php

declare(strict_types=1);

namespace App\Actions\Customer\Stripe;

use App\Exceptions\ApiException;
use App\Http\Services\StripeService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class GetPaymentLinkAction
{
    public function __construct(private readonly StripeService $stripeService) {}
    /**
     * Execute the action.
     */
    public function execute(User $user, string $price_id): string
    {
        return DB::transaction(function () use ($user, $price_id) {
            if ($user->isPremium()) {
                throw new ApiException('user_already_premium');
            }

            $checkoutSession = $this->stripeService->createPayment([
                'price_id' => $price_id,
                'customer_email' => $user->email,
            ]);

            return $checkoutSession->url;
        });
    }
}
