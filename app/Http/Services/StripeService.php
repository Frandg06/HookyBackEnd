<?php

declare(strict_types=1);

namespace App\Http\Services;

use Stripe\StripeClient;
use App\Enums\PaymentMode;
use App\Enums\PaymentMethodTypes;

final class StripeService
{
    private StripeClient $stripeClient;

    private string $successUrl;

    private string $failureUrl;

    private string $successSubUrl;

    private string $failureSubUrl;

    public function __construct()
    {
        $this->stripeClient = new StripeClient(config('services.stripe.secret'));
        $this->successUrl = config('services.stripe.success_url');
        $this->failureUrl = config('services.stripe.failure_url');
        $this->successSubUrl = config('services.stripe.success_sub_url');
        $this->failureSubUrl = config('services.stripe.failure_sub_url');
    }

    public function getClient(): StripeClient
    {
        return $this->stripeClient;
    }

    public function createSubscription(array $data)
    {
        return $this->stripeClient->checkout->sessions->create([
            'payment_method_types' => env('APP_ENV') === 'local' ? ['card'] : PaymentMethodTypes::values(),
            'line_items' => [[
                'price' => $data['price_id'],
                'quantity' => $data['quantity'] ?? 1,
            ]],
            'mode' => PaymentMode::SUBSCRIPTION->label(),
            'success_url' => $this->successSubUrl,
            'cancel_url' => $this->failureSubUrl,
            'allow_promotion_codes' => true,
        ]);
    }

    public function createPayment(array $data)
    {
        return $this->stripeClient->checkout->sessions->create([
            'payment_method_types' => env('APP_ENV') === 'local' ? ['card'] : PaymentMethodTypes::values(),
            'line_items' => [[
                'price' => $data['price_id'],
                'quantity' => $data['quantity'] ?? 1,
            ]],
            'mode' => PaymentMode::PAYMENT->label(),
            'customer_email' => $data['customer_email'] ?? null,
            'success_url' => $this->successUrl,
            'cancel_url' => $this->failureUrl,
            'allow_promotion_codes' => true,
        ]);
    }

    public function retrieveSession(string $sessionId)
    {
        return $this->stripeClient->checkout->sessions->retrieve($sessionId, [
            'expand' => ['line_items', 'payment_intent.payment_method'],
        ]);
    }
}
