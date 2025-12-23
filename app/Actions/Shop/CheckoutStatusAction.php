<?php

declare(strict_types=1);

namespace App\Actions\Shop;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Http\Services\StripeService;
use App\Repositories\OrderRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ProductRepository;

final readonly class CheckoutStatusAction
{
    public function __construct(
        private readonly StripeService $stripeService,
        private readonly OrderRepository $orderRepository,
        private readonly ProductRepository $productRepository,
        private readonly CompanyRepository $companyRepository,
    ) {}

    /**
     * Execute the action.
     */
    public function execute(string $sessionId): void
    {
        DB::transaction(function () use ($sessionId) {
            $session = $this->stripeService->retrieveSession($sessionId);

            if ($session->payment_status !== 'paid') {
                throw new ApiException('payment_not_completed', 422);
            }

            $priceId = $session->line_items->data[0]->price->id;
            $email = $session->customer_details->email;
            debug('Email retrieved from Stripe session: '.$email);

            // Create or update
            $company = $this->companyRepository->findCompanyByEmail($email);
            if (! $company) {
                throw new ApiException('company_not_found', 404);
            }
            $product = $this->productRepository->findProductByPriceId($priceId);

            if (! $product) {
                throw new ApiException('product_not_found', 404);
            }

            $this->orderRepository->createOrder([
                'company_uid' => $company->uid,
                'product_uuid' => $product->uuid,
                'order_number' => $session->id,
            ]);
        });
    }
}
