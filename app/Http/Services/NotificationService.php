<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

class NotificationService extends Service
{
    public function readNotificationsByType($type)
    {
        DB::beginTransaction();
        try {
            $user = $this->user();

            $user->notifications()->where('type_id', $type)->where('event_uid', $user->event->uid)->where('read_at', null)->update([
                'read_at' => now(),
            ]);

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
