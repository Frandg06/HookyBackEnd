<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Dtos\RegisterUserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Actions\Customer\Auth\RegisterAction;
use App\Actions\Customer\Auth\EventAttachAction;

final class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request, RegisterAction $action, EventAttachAction $attachAction)
    {

        $data = new RegisterUserDto(
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
            eventUid: $request->string('event_uid')->toString() ?: null,
        );

        $response = $action->execute($data);

        return $this->successResponse('Registration successful.', ['access_token' => $response], 201);
    }
}
