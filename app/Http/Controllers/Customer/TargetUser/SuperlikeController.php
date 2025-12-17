<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\TargetUser;

use App\Actions\Customer\TargetUser\RemoveTargetUserFromCacheAction;
use App\Actions\Customer\TargetUser\SuperlikeAction;
use App\DTO\InteractionDto;
use App\Enums\InteractionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\TargetUser\TargetUserRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;

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
