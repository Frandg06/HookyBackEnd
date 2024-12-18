<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Services\NotificationService;
use App\Models\Interaction;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class UserService
{
  private $notificationService;

  public function __construct(NotificationService $notificationService) {
    $this->notificationService = $notificationService;
    
  }

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
    DB::beginTransaction();
    try {

      UsersInteraction::where('user_uid', $authUser->uid)
        ->where('interaction_user_uid', $uid)
        ->update([
          'interaction_id' => $interaction,
          'is_confirmed' => in_array($interaction, [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID]) ? true : false
        ]);
      
      $authUser->refreshInteractions($interaction);

      
      if(in_array($interaction, [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])) {

        $checkHook =  UsersInteraction::where('user_uid', $uid)
                      ->where('interaction_user_uid', $authUser->uid)
                      ->whereIn('interaction_id',  [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
                      ->exists();

        if($checkHook) {
          $type = 'hook';

          $authNotification = [
            'user_uid' => $authUser->uid,
            'type' => $type,
            'data' => "Has obtenido un nuevo ". $type,
            'read_at' => null,
            'event_uid' => $authUser->event_uid,
          ];

          $this->notificationService->publishNotification($authNotification);

        }else {

          $type = ($interaction == Interaction::LIKE_ID) ? 'like' : 'superlike';

        }

        $newNotification = [
          'user_uid' => $uid,
          'type' => $type,
          'data' => "Has obtenido un nuevo ". $type,
          'read_at' => null,
          'event_uid' => $authUser->event_uid,
        ];

        $this->notificationService->publishNotification($newNotification);
      }

      DB::commit();

      return [
        "super_like_credits" => $authUser->super_like_credits,
        "like_credits" => $authUser->like_credits,
      ];
      
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error($e);
      throw new \Exception();
    }
  }
  
}