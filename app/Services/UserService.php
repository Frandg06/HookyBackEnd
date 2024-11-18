<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\Log;

class UserService
{

  public function getUsers(User $user) {
    try {
      // obtener los usuarios que no tienen interaccion con el usuario actual y que se han cargado previamente
      $existingusers = $user->interactions()->where('interaction_id', null)->get();

      // obtener los usuarios que no se han cargado previamente hasta un maximo de 50
      $remainingUsers = 50 - $existingusers->count();
        
      // obtener los usuarios que tienen intereses en comun
      if($remainingUsers > 0) {
        $lastUserIds = $user->interactions()->get()->pluck('interaction_user_id');

        $users = User::whereIn("gender_id", $user->match_gender)
                      ->where("sexual_orientation_id", $user->sexual_orientation_id)
                      ->whereNot('id', $user->id)
                      ->whereNotIn('id', $lastUserIds)
                      ->orWhereIn('id', $existingusers->pluck('interaction_user_id'))
                      ->limit($remainingUsers)
                      ->get();

        foreach ($users as $userToInsert) {  
          UsersInteraction::create([
            'user_id' => $user->id,
            'interaction_user_id' => $userToInsert->id,
            'interaction_id' => null,
          ]);
        }

      }else{
        $users = User::whereIn('id', $existingusers->pluck('interaction_user_id'))->get();
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
}