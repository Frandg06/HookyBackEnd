<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Image;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Image\SwapImageAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Http\Requests\Customer\Image\UpdateImageRequest;
use App\Actions\Customer\Image\DeleteImagePipeline\DeleteImagePassable;

final class SwapImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, UpdateImageRequest $request, SwapImageAction $action)
    {

        $deleteImagePassable = new DeleteImagePassable(
            user: $user,
            image_uid: $request->input('image_uid'),
            file: $request->file('image'),
        );

        $response = $action->execute($deleteImagePassable);

        return $this->successResponse('Image swapped successfully', $response);
    }
}
