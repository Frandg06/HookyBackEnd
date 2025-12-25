<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\TargetUser;

use App\Models\User;
use App\Dtos\InteractionDto;
use Illuminate\Http\Request;
use App\Enums\InteractionEnum;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\TargetUser\SuperlikeAction;
use App\Http\Requests\Customer\TargetUser\TargetUserRequest;
use App\Actions\Customer\TargetUser\RemoveTargetUserFromCacheAction;

final class SuperlikeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        #[CurrentUser()] User $user,
        TargetUserRequest $request,
        SuperlikeAction $action,
        RemoveTargetUserFromCacheAction $removeTargetUserFromCacheAction
    ) {
        $dto = new InteractionDto(
            $user->uid,
            $request->target_user_uid,
            $request->event_uid,
            InteractionEnum::SUPER_LIKE->toId(),
        );

        $response = $action->execute($user, $dto);
        $removeTargetUserFromCacheAction->execute($dto);

        return $this->successResponse(__('i18n.target_user_liked'), $response);
    }
}
