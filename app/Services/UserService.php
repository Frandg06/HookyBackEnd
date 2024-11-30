<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\Log;
/*
Si es la primera vez carga 50 usuarios de su horientacion y sexo necesario regustrados en el mismo evento



*/
class UserService
{

  public function getUsers(User $authUser) {
    try {
      // usuarios ya obtenidos previamente con lo que no se ha interactuado en el evento actual
      $usersWithoutInteraction = $authUser->interactions()->usersWithoutInteraction($authUser->event_uid);
      
      // usuarios que se han cargado previamente y que se ha interactuado en el evento actual
      $usersWithInteraction = $authUser->interactions()->usersWithInteraction($authUser->event_uid);

      // obtener los usuarios que se van a interactuar que esten en el evento que no se haya interactuado con ellos
      $users = User::getUsersToInteract($authUser, $usersWithInteraction, $usersWithoutInteraction);

      $newUsersWithInteractions = [];

      foreach ($users as $userToInsert) {  
        if(UsersInteraction::where('user_uid', $authUser->uid)->where('interaction_user_uid', $userToInsert->uid)->count() > 0) continue;
      
        $newUsersWithInteractions[] = [
          'user_uid' => $authUser->uid,
          'interaction_user_uid' => $userToInsert->uid,
          'interaction_id' => null,
          'event_uid' => $authUser->event_uid
        ];

      }

      UsersInteraction::insert($newUsersWithInteractions);

      return UserResource::collection($users);

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function setInteraction(User $authUser, $uid, $interaction) {
    try {

      UsersInteraction::where('user_uid', $authUser->uid)
        ->where('interaction_user_uid', $uid)
        ->update(['interaction_id' => $interaction]);
      
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