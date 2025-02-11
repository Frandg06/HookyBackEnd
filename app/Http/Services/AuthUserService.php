<?php
namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\AuthUserReosurce;
use App\Http\Resources\NotificationUserResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Interaction;
use App\Models\NotificationsType;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthUserService {

    public function update($data) {
      DB::beginTransaction();
      try {
        $user = request()->user();
        $user->update($data);

        if( isset($user['gender_id']) || isset($user['sexual_orientation_id'] )) {
          $user->interactions()->delete();
        }
        
        DB::commit();
        return AuthUserReosurce::make($user);

      } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__("i18n.update_user_ko"));
      }

    }

    public function updatePassword($data, $user) {
      try {
        
        if(!Hash::check($data['old_password'], $user->password)) {
          throw new ApiException(__("i18n.actual_password_ko"));
        }

        $user->password = bcrypt($data['password']);
        $user->save();

        return true;

      } catch (ApiException $e) {
        throw new \Exception($e->getMessage());
      } catch (\Exception $e) {
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__("i18n.update_password_ko"));
      }
    }

    public function updateInterest(User $user, $newInterests) {
      try {
  
        $hasInterest = $user->interests()->get()->pluck('interest_id')->toArray();
  
        foreach ($newInterests as $item) {
          if(!in_array($item, $hasInterest)) {
            $user->interests()->create([
              'interest_id' => $item
            ]);
          }
        }
  
        foreach ($hasInterest as $item) {
          if(!in_array($item, $newInterests)) {
            $user->interests()->where('interest_id', $item)->delete();
          }
        }
  
        return $user;
    
      } catch (\Exception $e) {
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__("i18n.update_user_interest_ko"));
      }
  
    }

    public function getNotifications(User $user) {

      try {

        $usersHook = $user->notifications()->where('type_id', NotificationsType::HOOK_TYPE)->where('event_uid', $user->event_uid)->get();

        $userLikes = $user->notifications()->where('type_id', NotificationsType::LIKE_TYPE)->where('event_uid', $user->event_uid)->get();
        
        $likes_count = $userLikes->count();

        if($user->role_id == Role::ROLE_PREMIUM) {
          $likes = NotificationUserResource::collection($userLikes);
        } else {
          $likes['images'] = $userLikes->take(7)->map(function ($u) use ($user) {
            return $u->user->userImages()->first()->web_url;
          });
          $likes['count'] = $likes_count;
        }

        $userSuperLikes =  $user->notifications()->where('type_id', NotificationsType::SUPER_LIKE_TYPE)->where('event_uid', $user->event_uid)->get();

        $data = [
          "likes" => $likes,
          "super_likes" => NotificationUserResource::collection($userSuperLikes),
          "hooks" => NotificationUserResource::collection($usersHook)
        ];

        return $data;

      }catch (\Exception $e) {
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__("i18n.get_notifications_ko"));
      }
      
    }
}