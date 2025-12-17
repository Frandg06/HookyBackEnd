<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image;

use App\Models\User;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;

final readonly class OrderImageAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, string $imageUid, string $direction): void
    {
        DB::transaction(function () use ($user, $imageUid, $direction) {
            $user->load('userImages');
            $image = $user->userImages->where('uid', $imageUid)->first();

            $currentOrder = $image->order;

            $newOrder = match ($direction) {
                'up' => $currentOrder - 1,
                'down' => $currentOrder + 1,
                default => $currentOrder,
            };

            if ($newOrder < 1 || $newOrder > 3) {
                throw new ApiException('image_order_limit', 422);
            }

            $user->userImages->where('order', $newOrder)->first()->update(['order' => $currentOrder]);

            $image->update(['order' => $newOrder]);
        });
    }
}
