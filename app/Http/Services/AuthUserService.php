<?php

declare(strict_types=1);

namespace App\Http\Services;

use Exception;
use App\Exceptions\ApiException;
use App\Models\NotificationsType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\NotificationUserResource;

final class AuthUserService extends Service
{
    public function updatePassword(array $data)
    {
        DB::beginTransaction();
        try {
            if ($this->user()->auto_password === false && ! Hash::check($data['old_password'], $this->user()->password)) {
                throw new ApiException('actual_password_ko', 400);
            }

            $this->user()->password = bcrypt($data['password']);
            $this->user()->auto_password = false;
            $this->user()->save();

            DB::commit();

            return $this->user()->toResource();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getNotifications()
    {
        $user = user();

        $query = $user->notifications()->where('event_uid', $user->event->uid)->get();

        [$hooks, $likes, $superlikes] = [
            $query->where('type_id', NotificationsType::HOOK_TYPE),
            $query->where('type_id', NotificationsType::LIKE_TYPE),
            $query->where('type_id', NotificationsType::SUPER_LIKE_TYPE),
        ];

        $data = [
            'likes' => user()->isPremium ? NotificationUserResource::collection($likes) : [
                'images' => $likes->take(7)->map(function ($u) {
                    return $u->user->images()->first()->web_url;
                }),
                'count' => $likes->count(),
            ],
            'super_likes' => NotificationUserResource::collection($superlikes),
            'hooks' => NotificationUserResource::collection($hooks),
        ];

        return $data;
    }
}
