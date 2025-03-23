<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{

    public function publishNotification($data)
    {
        try {

            Notification::create($data);

            $sendData = [
              'userToNotify' => $data['user_uid'],
              'type_id'      => $data['type_id'],
              'type_str'     => $data['type_str']
            ];

            $this->emitNotification($sendData);
        } catch (\Exception $e) {
            throw new ApiException('notification_ko', 500);
        }
    }

    public function readNotificationsByType($type)
    {
        DB::beginTransaction();
        try {
            $user = request()->user();

            $notifications = $user->notifications()->where('type_id', $type)->where('event_uid', $user->event_uid)->where('read_at', null)->get();

            $notifications->each(function ($notification) {
                $notification->read_at = now();
                $notification->save();
            });

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException('read_notifications_ko', 500);
        }
    }

    private function emitNotification($data)
    {
        try {
            $notify_url = config('services.ws_api.notify_url');

            Http::withHeaders([
              'Authorization' => 'Bearer ' . request()->bearerToken(),
              'Accept' => 'application/json'
            ])->post($notify_url, $data);
        } catch (\Exception $e) {
            throw new ApiException('notification_ko', 500);
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
        }
    }
}
