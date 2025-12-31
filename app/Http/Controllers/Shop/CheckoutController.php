<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Actions\Shop\CheckoutAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CheckoutRequest;

final class CheckoutController extends Controller
{
    public function __invoke(CheckoutRequest $request, CheckoutAction $action)
    {
        $price_id = $request->input('price_id');
        $payment_url = $action->execute($price_id);

        return $this->successResponse('checkout_session_created', $payment_url, 201);

    }
}
