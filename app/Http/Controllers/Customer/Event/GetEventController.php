<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Event;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Event\GetEventAction;

final class GetEventController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $slug, GetEventAction $action)
    {
        $response = $action->execute($slug);

        return $this->successResponse('i18n.event_retrieved_successfully', $response);
    }
}
