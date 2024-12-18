<?php
namespace App\Http\Services;

use App\Models\Notification;

class NotificationService {

  public function publishNotification($data) {
    try {
      
      $notification = Notification::create($data);

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
}