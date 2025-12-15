<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\PaymentMethodTypes;
use App\Enums\PaymentMode;
use Stripe\StripeClient;

final class StripeService
{
    private StripeClient $stripeClient;

    private string $successUrl;

    private string $failureUrl;

    public function __construct()
    {
        $this->stripeClient = new StripeClient(config('services.stripe.secret'));
        $this->successUrl = config('services.stripe.success_url');
        $this->failureUrl = config('services.stripe.failure_url');
    }

    public function getClient(): StripeClient
    {
        return $this->stripeClient;
    }

    public function createSubscription(array $data)
    {
        return $this->stripeClient->checkout->sessions->create([
            'payment_method_types' => PaymentMethodTypes::values(),
            'line_items' => [[
                'price' => $data['price_id'],
                'quantity' => $data['quantity'] ?? 1,
            ]],
            'mode' => PaymentMode::SUBSCRIPTION->label(),
            'customer_email' => $data['customer_email'] ?? null,
            'success_url' => $this->successUrl,
            'cancel_url' => $this->failureUrl,
            'allow_promotion_codes' => true,
        ]);
    }

    public function createPayment(array $data)
    {
        return $this->stripeClient->checkout->sessions->create([
            'payment_method_types' => PaymentMethodTypes::values(),
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
        return $this->stripeClient->checkout->sessions->retrieve($sessionId);
    }
}
