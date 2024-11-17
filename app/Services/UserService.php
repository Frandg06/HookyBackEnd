<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\Log;

class UserService
{

  public function getUsers(User $user) {

    $gender = $this->getUsersGender($user->gender_id, $user->sexual_orientation_id);
    
    try {
      // obtener los usuarios que no tienen interaccion con el usuario actual y que se han cargado previamente
      $userIds = $user->interactions()->where('interaction_id', null)->get()->pluck('interaction_user_id');
      $existingusers = User::whereIn('id', $userIds)->get();

      // obtener los usuarios que no se han cargado previamente hasta un maximo de 50
      $remainingUsers = 50 - $existingusers->count();
        
      // obtener los usuarios que tienen intereses en comun
      if($remainingUsers > 0) {
        $lastUserIds = $user->interactions()->get()->pluck('interaction_user_id');
        $newUsers = User::where("gender_id", $gender)
                      ->where("sexual_orientation_id", $user->sexual_orientation_id)
                      ->whereNot('id', $user->id)
                      ->whereNotIn('id', $lastUserIds)
                      ->limit($remainingUsers)
                      ->get();

        foreach ($newUsers as $userToInsert) {  
          UsersInteraction::create([
            'user_id' => $user->id,
            'interaction_user_id' => $userToInsert->id,
            'interaction_id' => null,
          ]);
        }
        $users = $existingusers->merge($newUsers);
      }else{
        $users = $existingusers;
      }

      return UserResource::collection($users);

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

  public function setInteraction(User $user, $id, $interaction) {
    try {
      UsersInteraction::where('user_id', $user->id)->where('interaction_user_id', $id)->update(['interaction_id' => $interaction]);
      return true;
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  private function getUsersGender($genderId, $sexualOrientationId) {
    if($sexualOrientationId == 2) {
      return $genderId == 1 ? 2 : 1;
    } elseif ($sexualOrientationId == 3) {
      return $genderId;
    }
  }
}