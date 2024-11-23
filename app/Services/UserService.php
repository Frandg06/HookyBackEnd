<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\Interaction;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\Log;

class UserService
{

  public function getUsers(User $authUser) {
    try {
      // obtener los usuarios que no tienen interaccion con el usuario actual y que se han cargado previamente
      $idsWithNoInteraction = $authUser->interactions()->where('interaction_id', null)->get()->pluck('interaction_user_id');
      $usersWithNoInteraction = User::whereIn('id', $idsWithNoInteraction)->get();
      // obtener los usuarios que no se han cargado previamente hasta un maximo de 50
      $remainingUsers = 50 - $idsWithNoInteraction->count();
      
      // obtener los usuarios que tienen intereses en comun
      if($remainingUsers > 0) {
        $usersRegistered = $authUser->interactions()->get()->pluck('interaction_user_id');

        $newUsersWithNoInteraction = User::whereIn("gender_id", $authUser->match_gender)
                      ->where("sexual_orientation_id", $authUser->sexual_orientation_id)
                      ->whereNot('id', $authUser->id)
                      ->whereNotIn('id', $usersRegistered)
                      ->whereHas('interests', function ($query) {
                        $query->select('user_id') // Asegura contar imágenes por usuario
                        ->groupBy('user_id')
                        ->havingRaw('COUNT(*) BETWEEN 3 AND 6');
                      })
                      ->whereHas('userImages', function ($query) {
                        $query->select('user_id') // Asegura contar imágenes por usuario
                        ->groupBy('user_id')
                        ->havingRaw('COUNT(*) = 3');
                      })
                      ->limit($remainingUsers)
                      ->get();

        foreach ($newUsersWithNoInteraction as $userToInsert) {  
          UsersInteraction::create([
            'user_id' => $authUser->id,
            'interaction_user_id' => $userToInsert->id,
            'interaction_id' => null,
          ]);
        }

        $users = $usersWithNoInteraction->merge($newUsersWithNoInteraction);

      }else{
        $users = User::whereIn('id', $idsWithNoInteraction)->get();
      }

      return UserResource::collection($users);

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function setInteraction(User $authUser, $id, $interaction) {
    try {

      UsersInteraction::where('user_id', $authUser->id)->where('interaction_user_id', $id)->update(['interaction_id' => $interaction]);
      
      $authUser->refreshInteractions($interaction);

      return [
        "super_like_credits" => $authUser->super_like_credits,
        "like_credits" => $authUser->like_credits,
      ];
      
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
  
}