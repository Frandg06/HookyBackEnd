<?php
namespace App\Http\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService {

  public function publishNotification($data) {
    try {
      
      $notification = Notification::create($data);

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function readNotificationsByType(User $user, $type) {
    try {
      $notifications = $user->notifications()->where('type', $type)->where('event_uid', $user->event_uid)->where('read_at', null)->get();
      
      $notifications->each(function ($notification) {
        $notification->read_at = now();
        $notification->save();
      });

      return true;

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
}