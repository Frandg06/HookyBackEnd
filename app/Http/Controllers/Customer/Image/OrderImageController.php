<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Image;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Auth\MeAction;
use App\Actions\Customer\Image\OrderImageAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Http\Requests\Customer\Image\ImageOrderRequest;

final class OrderImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, ImageOrderRequest $request, OrderImageAction $imageAction, MeAction $userAction)
    {
        $imageAction->execute($user, $request->input('image_uid'), $request->input('direction'));
        $user = $userAction->execute($user);

        return $this->successResponse(__('i18n.image_order_updated'), $user);
    }
}
