<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteUserAction
{
    /**
     * Execute the action to delete the authenticated user.
     */
    public function execute(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            foreach ($user->images as $image) {
                Storage::disk('r2')->delete($image->url);
            }

            return $user->delete();
        });
    }
}
