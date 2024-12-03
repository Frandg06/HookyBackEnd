<?php
namespace App\Services;

use App\Http\Resources\AuthUserReosurce;
use App\Http\Resources\NotificationUserResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Interaction;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthUserService {

    public function update(User $user, $data) {
      try {

        $user->update($data);

        if( isset($user['gender_id']) || isset( $user['sexual_orientation_id'] )) {
          $user->interactions()->delete();
        }
        
        return AuthUserReosurce::make($user);

      } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
      }

    }

    public function setEvent($request, $company_uid) {
      try {
        
        if(!$request->user()){
          throw new \Exception("The user not exist");
        }

        $company = Company::where('uid', $company_uid)->first();

        $event = $company->events()->latest()->first()->uid;

        Log::info($company);
        
        $request->user()->event_uid = $event;
        $request->user()->save();

        return $request->user()->event_uid;

      } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
      }

     
    }

    public function updatePassword($data, $user) {
      try {
        
        if(!Hash::check($data['old_password'], $user->password)) {
          throw new \Exception("La contraseña actual es incorrecta");
        }

        $user->password = bcrypt($data['password']);
        $user->save();

        return true;
      } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
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
        throw new \Exception($e->getMessage());
      }
  
    }

    public function getLikes(User $user) {

      try {

        $usersHook =  $user->getUserHooks();

        $userLikes =$user->scopeGetUserLikes();
        Log::info($userLikes);

        $userLikes = $userLikes->map(function ($u) use ($user) {
          return $user->role_id == User::ROLE_PREMIUM 
            ? NotificationUserResource::make($u)
            : $u->userImages()->first()->web_url;
        });

        $userSuperLikes =  $user->getUserSuperLikes();

        return [
          "likes" => $userLikes,
          "super_likes" => $userSuperLikes,
          "hooks" => NotificationUserResource::collection($usersHook)
        ];

      }catch (\Exception $e) {
        Log::error($e);
        throw new \Exception("Error al obtener las notificaciones");
      }
      
    }
}