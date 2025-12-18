<?php

declare(strict_types=1);

namespace App\Actions\Customer\Stripe;

use App\Models\Role;
use App\Models\User;
use Stripe\Checkout\Session;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;

final readonly class PaymentAction
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly ProductRepository $productRepository,
    ) {}

    public function execute(User $user, Session $session): void
    {
        DB::transaction(function () use ($user, $session) {
            if ($session->payment_status !== 'paid') {
                throw new ApiException('payment_not_completed', 422);
            }

            $priceId = $session->line_items->data[0]->price->id;
            $product = $this->productRepository->findProductByPriceId($priceId);

            if (! $product) {
                throw new ApiException('product_not_found', 404);
            }

            $this->orderRepository->createOrder([
                'user_uid' => $user->uid,
                'product_uuid' => $product->uuid,
                'order_number' => $session->id,
            ]);

            $user->update(['role_id' => Role::PREMIUM]);
        });
    }
}
