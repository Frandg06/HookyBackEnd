<?php

declare(strict_types=1);

namespace App\Actions\Customer\Chat;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\ChatRepository;
use App\Http\Resources\Customer\Chat\ChatPreviewResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class GetChatsAction
{
    public function __construct(private readonly ChatRepository $chat_repository) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, int $page = 1): AnonymousResourceCollection
    {
        return DB::transaction(function () use ($user, $page) {
            $chats = $this->chat_repository->getChatsFromUser($user, $page);

            return ChatPreviewResource::collection($chats);
        });
    }
}
