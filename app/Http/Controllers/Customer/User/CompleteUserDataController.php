<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Dtos\CompleteUserDataDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteAuthUserRequest;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\User\CompleteUserAction;

final class CompleteUserDataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, CompleteAuthUserRequest $request, CompleteUserAction $action)
    {
        $data = new CompleteUserDataDto(
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            born_date: $request->string('born_date')->toString(),
            sexual_orientation: $request->string('sexual_orientation')->toString(),
            gender: $request->string('gender')->toString(),
            description: $request->string('description')->toString(),
            files: $request->userImages,
        );

        $user = $action->execute($user, $data);

        return $this->successResponse('i18n.user_data_completed');
    }
}
