<?php

declare(strict_types=1);

namespace App\Actions\Shop;

use Illuminate\Support\Facades\DB;
use App\Http\Services\StripeService;

final readonly class CheckoutAction
{
    /**
     * Execute the action.
     */
    public function __construct(
        private readonly StripeService $stripeService,
    ) {}

    /**
     * Execute the action.
     */
    public function execute(string $price_id): string
    {
        return DB::transaction(function () use ($price_id) {

            $checkoutSession = $this->stripeService->createSubscription([
                'price_id' => $price_id,
            ]);

            return $checkoutSession->url;
        });
    }
}
