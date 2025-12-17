<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\TargetUser;

use App\Models\User;
use App\DTO\InteractionDto;
use Illuminate\Http\Request;
use App\Enums\InteractionEnum;
use App\Http\Controllers\Controller;
use App\Actions\Customer\TargetUser\LikeAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Http\Requests\Customer\TargetUser\TargetUserRequest;
use App\Actions\Customer\TargetUser\RemoveTargetUserFromCacheAction;

final class LikeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        #[CurrentUser()] User $user,
        TargetUserRequest $request,
        LikeAction $likeAction,
        RemoveTargetUserFromCacheAction $removeTargetUserFromCacheAction
    ) {
        $dto = new InteractionDto(
            $user->uid,
            $request->target_user_uid,
            $request->event_uid,
            InteractionEnum::LIKE->toId(),
        );

        $response = $likeAction->execute($user, $dto);

        $removeTargetUserFromCacheAction->execute($dto);

        return $this->successResponse(__('i18n.target_user_liked'), $response);
    }
}
