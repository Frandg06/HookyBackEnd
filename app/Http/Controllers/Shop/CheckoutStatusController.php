<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Shop\CheckoutStatusAction;

final class CheckoutStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CheckoutStatusAction $action)
    {
        $sessionId = $request->input('session_id');
        $action->execute($sessionId);
    }
}
