<?php
namespace App\Http\Services;

use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService {

  public function publishNotification($data) {
    try {
      
      Notification::create($data);

      $sendData = [
        'userToNotify' => $data['user_uid'],
        'type_id'      => $data['type_id'],
        'type_str'     => $data['type_str']
      ];

      $this->emitNotification($sendData);

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function readNotificationsByType(User $user, $type) {
    try {
      $notifications = $user->notifications()->where('type_id', $type)->where('event_uid', $user->event_uid)->where('read_at', null)->get();
      
      $notifications->each(function ($notification) {
        $notification->read_at = now();
        $notification->save();
      });

      return true;

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  private function emitNotification($data) {
    try {
      $notify_url = config('services.ws_api.notify_url');
      
      Http::withHeaders([
        'Authorization' => 'Bearer '.request()->bearerToken(),
        'Accept' => 'application/json'
      ])->post($notify_url, $data);

    } catch (Exception $e) {
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
    }
  }
}