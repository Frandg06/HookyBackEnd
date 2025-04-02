<?php

namespace App\Http\Services;

use App\Models\Chat;
use Throwable;

class ChatService extends Service
{
  public function retrieve(array $data): array
  {
    // Implement the logic to retrieve chat data
    return [
      'success' => true,
      'data' => [], // Replace with actual data retrieval logic
    ];
  }

  public function show(array $data): array
  {
    // Implement the logic to show messages
    return [
      'success' => true,
      'messages' => [], // Replace with actual message retrieval logic
    ];
  }

  public function store($user1, $user2, $event)
  {
    try {
      $chat = Chat::create([
        'user1_uid' => $user1,
        'user2_uid' => $user2,
        'event_uid' => $event,
        'created_at' => now()
      ]);

      return $chat;
    } catch (Throwable $e) {
      $this->logError($e, __CLASS__, __FUNCTION__);
      $this->responseError('Error storing chat data', 500);
    }
  }
}
