<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Image;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\Image\DeleteImageAction;
use App\Http\Requests\Customer\Image\DeleteImageRequest;

final class DeleteImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, DeleteImageRequest $request, DeleteImageAction $action)
    {
        $response = $action->execute($user, $request->input('image_uid'));

        return $this->successResponse('Image deleted successfully', $response);
    }
}
