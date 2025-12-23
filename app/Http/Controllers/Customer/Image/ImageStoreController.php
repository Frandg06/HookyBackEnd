<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Image;

use App\Models\User;
use App\Dtos\StoreImageDto;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Image\StoreImageAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Http\Requests\Customer\Image\ImageStoreRequest;

final class ImageStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, ImageStoreRequest $request, StoreImageAction $action)
    {
        $data = new StoreImageDto(
            files: [$request->image]
        );

        $user = $action->execute($user, $data);

        return $this->successResponse(__('i18n.image_store_ok'), $user->refresh()->toResource());
    }
}
