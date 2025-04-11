<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\NotificationUserResource;
use App\Models\NotificationsType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthUserService extends Service
{
    public function update(array $data)
    {
        DB::beginTransaction();
        try {
            $existingUser = User::where('email', $data['email'])->whereNot('uid', $this->user()->uid)->first();

            if ($existingUser) {
                throw new ApiException('user_exists', 409);
            }

            $this->user()->update($data);

            if (isset($data['gender_id']) || isset($data['sexual_orientation_id'])) {
                $this->user()->interactions()->delete();
            }

            DB::commit();

            return $this->user()->resource();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatePassword(array $data)
    {
        DB::beginTransaction();
        try {
            if (!Hash::check($data['old_password'], $this->user()->password)) {
                throw new ApiException('actual_password_ko', 400);
            }

            $this->user()->password = bcrypt($data['password']);
            $this->user()->save();

            DB::commit();

            return $this->user()->resource();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getNotifications()
    {
        $user = $this->user();

        $notifications = $user->notifications()->where('event_uid', $user->event->uid)->get();

        $usersHook = $notifications->where('type_id', NotificationsType::HOOK_TYPE);

        $userLikes = $notifications->where('type_id', NotificationsType::LIKE_TYPE);

        $userSuperLikes =  $notifications->where('type_id', NotificationsType::SUPER_LIKE_TYPE);

        $likes_count = $userLikes->count();

        if ($user->role_id == Role::PREMIUM) {
            $likes = NotificationUserResource::collection($userLikes);
        } else {
            $likes['images'] = $userLikes->take(7)->map(function ($u) use ($user) {
                return $u->user->userImages()->first()->web_url;
            });
            $likes['count'] = $likes_count;
        }


        $data = [
            'likes' => $likes,
            'super_likes' => NotificationUserResource::collection($userSuperLikes),
            'hooks' => NotificationUserResource::collection($usersHook)
        ];

        return $data;
    }

    public function completeRegisterData($info, $files)
    {
        DB::beginTransaction();
        try {
            $user = request()->user();
            $this->update($info);

            $imageService = new ImagesService();

            if ($user->userImages()->count() === 0) {
                if (count($files) < 3 || count($files) > 3) {
                    throw new ApiException('invalid_image_count', 400);
                }
                foreach ($files as $file) {
                    $imageService->store($file['file'], $file['data']);
                }
            }

            DB::commit();

            return $user->resource();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw $e;
        }
    }
}
