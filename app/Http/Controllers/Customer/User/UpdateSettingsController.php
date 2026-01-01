<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User;

use App\Models\User;
use App\Dtos\UpdateSettingsDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\User\UpdateSettingsAction;

final class UpdateSettingsController extends Controller
{
    public function __invoke(#[CurrentUser()] User $user, UpdateSettingsRequest $request, UpdateSettingsAction $action)
    {
        $data = new UpdateSettingsDto(
            new_like_notification: $request->boolean('new_like_notification'),
            new_superlike_notification: $request->boolean('new_superlike_notification'),
            new_message_notification: $request->boolean('new_message_notification'),
            event_start_email: $request->boolean('event_start_email'),
        );

        $response = $action->execute($user, $data);

        $this->successResponse('i18n.settings_updated', $response);
    }
}
