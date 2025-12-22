<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\CompleteUserPipeline;

use Closure;
use App\Models\User;

final class UpdateUserPipe
{
    /**
     * Check if a user is changing their email address to another one that already exists in the database.
     */
    public function handle(CompleteUserDataPassable $passable, Closure $next): CompleteUserDataPassable
    {
        $passable->user->update([
            'name' => $passable->data->name,
            'email' => $passable->data->email,
            'born_date' => $passable->data->born_date,
            'sexual_orientation' => $passable->data->sexual_orientation,
            'gender' => $passable->data->gender,
            'description' => $passable->data->description,
        ]);

        return $next($passable);
    }
}
