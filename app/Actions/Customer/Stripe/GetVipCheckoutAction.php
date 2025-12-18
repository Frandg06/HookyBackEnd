<?php

declare(strict_types=1);

namespace App\Actions\Customer\Stripe;

use App\Models\User;
use App\Enums\ProductEnum;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Http\Services\StripeService;
use App\Repositories\ProductRepository;

final readonly class GetVipCheckoutAction
{
    public function __construct(
        private readonly StripeService $stripeService,
        private readonly ProductRepository $productRepository,
    ) {}

    /**
     * Execute the action.
     */
    public function execute(User $user): string
    {
        return DB::transaction(function () use ($user) {
            if ($user->isPremium()) {
                throw new ApiException('user_already_premium');
            }

            $product = $this->productRepository->findProductByName(ProductEnum::VIP->label());

            $checkoutSession = $this->stripeService->createPayment([
                'price_id' => $product->price_id,
                'customer_email' => $user->email,
            ]);

            return $checkoutSession->url;
        });
    }
}
