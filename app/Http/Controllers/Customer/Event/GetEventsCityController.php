<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Event;

use App\Actions\Customer\Events\GetEventsCityAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

final class GetEventsCityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetEventsCityAction $action)
    {
        $response = $action->execute();

        return $this->successResponse(__('i18n.cities_retrieved_successfully'), $response);
    }
}
