<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\CompleteUserPipeline;

use Closure;
use App\Models\User;
use Illuminate\Validation\ValidationException;

final class ValidateUserEmail
{
    /**
     * Check if a user is changing their email address to another one that already exists in the database.
     */
    public function handle(CompleteUserDataPassable $passable, Closure $next): CompleteUserDataPassable
    {
        $existingMail = User::where('email', $passable->data->email)->whereNot('uid', $passable->user->uid)->exists();

        if ($existingMail) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        return $next($passable);
    }
}
