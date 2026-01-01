<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User;

use App\Models\User;
use App\Dtos\InteractionDto;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\User\ShowTargetUserAction;
use App\Http\Requests\Customer\User\ShowTargetUserRequest;

final class ShowTargetUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, ShowTargetUserRequest $request, ShowTargetUserAction $action)
    {
        $dto = new InteractionDto(
            user_uid: $user->uid,
            target_user_uid: $request->string('user_uid')->toString(),
            event_uid: $user->event->uid,
            interaction: null,
        );

        $response = $action->execute($dto);

        return $this->successResponse('User to confirm retrieved', $response);
    }
}
