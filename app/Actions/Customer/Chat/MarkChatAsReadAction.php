<?php

declare(strict_types=1);

namespace App\Actions\Customer\Chat;

use App\Models\User;
use App\Repositories\ChatRepository;

final readonly class MarkChatAsReadAction
{
    public function __construct(
        private ChatRepository $chatRepository
    ) {}

    /**
     * Execute the action to mark all messages in a chat as read.
     */
    public function execute(User $user, string $chatUid): void
    {
        $this->chatRepository->markMessagesAsRead($chatUid, $user->uid);
    }
}
