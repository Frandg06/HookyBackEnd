<?php

declare(strict_types=1);

namespace App\Actions\Customer\Chat;

use App\Events\MessageNotificationEvent;
use App\Events\PrivateChatMessageEvent;
use App\Http\Resources\MessageResource;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

final readonly class SendMessageAction
{
    public function __construct(private readonly ChatRepository $chatRepository, private readonly UserRepository $userRepository) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, string $chat_uid, string $message)
    {
        return DB::transaction(function () use ($user, $chat_uid, $message) {

            $chat = $this->chatRepository->findByUuid($chat_uid);
            $user_uid = $user->uid === $chat->user1_uid ? $chat->user2_uid : $chat->user1_uid;
            $target_user = $this->userRepository->findByUuid($user_uid);

            $message = $this->chatRepository->storeMessage(
                chat_uid: $chat_uid,
                sender_uid: $user->uid,
                receiver_uid: $target_user->uid,
                message: $message,
            );

            MessageNotificationEvent::dispatch($target_user, $message);
            PrivateChatMessageEvent::dispatch($message);

            return MessageResource::make($message);
        });
    }
}
