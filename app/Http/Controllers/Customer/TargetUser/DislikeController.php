<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\TargetUser;

use App\Actions\Customer\TargetUser\DislikeAction;
use App\Actions\Customer\TargetUser\RemoveTargetUserFromCacheAction;
use App\DTO\InteractionDto;
use App\Enums\InteractionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\TargetUser\TargetUserRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;

final class DislikeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        #[CurrentUser()] User $user,
        TargetUserRequest $request,
        DislikeAction $dislikeAction,
        RemoveTargetUserFromCacheAction $removeTargetUserFromCacheAction
    ) {
        $dto = new InteractionDto(
            $user->uid,
            $request->target_user_uid,
            $request->event_uid,
            InteractionEnum::DISLIKE->toId(),
        );

        $response = $dislikeAction->execute($user, $dto);

        $removeTargetUserFromCacheAction->execute($dto);

        return $this->successResponse('target_user_disliked', $response);
    }
}
