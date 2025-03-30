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
        try {;
            $existingUser = User::where('email', $data['email'])->whereNot('uid', $this->user()->uid)->first();

            if ($existingUser) {
                throw new ApiException('user_exists', 409);
            }

            $this->user()->update($data);

            if (isset($user['gender_id']) || isset($user['sexual_orientation_id'])) {
                $user->interactions()->delete();
            }

            DB::commit();

            return $this->user()->resource();
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('update_company_ko');
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
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('update_password_ko', 500);
        }
    }

    public function updateInterest(array $newInterests)
    {
        DB::beginTransaction();
        try {
            $hasInterest = $this->user()->interests()->get()->pluck('interest_id')->toArray();

            foreach ($newInterests as $item) {
                if (!in_array($item, $hasInterest)) {
                    $this->user()->interests()->create([
                        'interest_id' => $item
                    ]);
                }
            }

            foreach ($hasInterest as $item) {
                if (!in_array($item, $newInterests)) {
                    $this->user()->interests()->where('interest_id', $item)->delete();
                }
            }

            DB::commit();

            return $this->user()->resource();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('update_user_interest_ko', 500);
        }
    }

    public function getNotifications()
    {
        DB::beginTransaction();
        try {
            $user = request()->user();

            $usersHook = $user->notifications()->where('type_id', NotificationsType::HOOK_TYPE)->where('event_uid', $user->event_uid)->get();

            $userLikes = $user->notifications()->where('type_id', NotificationsType::LIKE_TYPE)->where('event_uid', $user->event_uid)->get();

            $likes_count = $userLikes->count();

            if ($user->role_id == Role::PREMIUM) {
                $likes = NotificationUserResource::collection($userLikes);
            } else {
                $likes['images'] = $userLikes->take(7)->map(function ($u) use ($user) {
                    return $u->user->userImages()->first()->web_url;
                });
                $likes['count'] = $likes_count;
            }

            $userSuperLikes =  $user->notifications()->where('type_id', NotificationsType::SUPER_LIKE_TYPE)->where('event_uid', $user->event_uid)->get();

            $data = [
                'likes' => $likes,
                'super_likes' => NotificationUserResource::collection($userSuperLikes),
                'hooks' => NotificationUserResource::collection($usersHook)
            ];

            DB::commit();

            return $data;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('get_notifications_ko', 500);
        }
    }

    public function completeRegisterData($info, $files, $interests)
    {
        DB::beginTransaction();
        try {
            $user = request()->user();
            $this->update($info);
            $this->updateInterest($interests);

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
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('update_company_ko', 500);
        }
    }
}
