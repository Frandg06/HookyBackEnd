<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Image;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\Image\ClearUserImagesAction;

final class ClearUserImagesController extends Controller
{
    /**
     * Handle the incoming request to delete all user images.
     */
    public function __invoke(#[CurrentUser()] User $user, ClearUserImagesAction $action)
    {
        $action->execute($user);

        return $this->successResponse('All images deleted successfully');
    }
}
