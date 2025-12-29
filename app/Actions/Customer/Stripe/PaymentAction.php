<?php

declare(strict_types=1);

namespace App\Actions\Customer\Stripe;

use App\Models\Role;
use App\Models\User;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Http\Services\StripeService;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;

final readonly class PaymentAction
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly ProductRepository $productRepository,
        private readonly StripeService $stripeService,
    ) {}

    public function execute(User $user, string $sessionId): array
    {
        return DB::transaction(function () use ($user, $sessionId) {

            $session = $this->stripeService->retrieveSession($sessionId);

            debug($session);
            if ($session->payment_status !== 'paid') {
                throw new ApiException('payment_not_completed', 422);
            }

            $priceId = $session->line_items->data[0]->price->id;

            if ($session->amount_total > 0) {
                $cardBrand = $session->payment_intent->payment_method->card->brand;
                $last4 = $session->payment_intent->payment_method->card->last4;
            }

            $product = $this->productRepository->findProductByPriceId($priceId);

            if (! $product) {
                throw new ApiException('product_not_found', 404);
            }

            $order = $this->orderRepository->firstOrCreateOrder([
                'user_uid' => $user->uid,
                'product_uuid' => $product->uuid,
                'session_id' => $session->id,
            ]);

            $user->update(['role_id' => Role::PREMIUM]);

            return [
                'success' => true,
                'order_number' => $order->order_number,
                'amount_total' => $session->amount_total / 100,
                'currency' => $session->currency,
                'last4' => $last4 ?? null,
                'card_brand' => $cardBrand ?? null,
                'created_at' => $order->created_at->toDateTimeString(),
            ];
        });
    }
}
