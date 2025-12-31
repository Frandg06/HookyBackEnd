<?php

declare(strict_types=1);

namespace App\Actions\Customer\Chat;

use Illuminate\Support\Facades\DB;
use App\Repositories\ChatRepository;
use App\Exceptions\NotFoundException;
use App\Http\Resources\Customer\Chat\ChatCollection;

final readonly class ShowChatAction
{
    public function __construct(private readonly ChatRepository $chat_repository) {}

    public function execute(string $chat_uid, int $page = 1): ChatCollection
    {
        return DB::transaction(function () use ($chat_uid, $page) {

            $chat = $this->chat_repository->findByUuid($chat_uid);

            throw_if(! $chat, NotFoundException::chat());

            $messages = $this->chat_repository->getMessagesFromChat($chat, $page);

            return ChatCollection::make($messages)->withChat($chat);
        });
    }
}
