<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Services\NotificationService;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Matcher\Not;

class UserService
{
  protected $notificationService;

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
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new \Exception(__('i18n.get_users_ko'));
    }
  }

  public function setInteraction(User $authUser, $uid, $interaction) {
    DB::beginTransaction();
    try {

      UsersInteraction::where('user_uid', $authUser->uid)
        ->where('interaction_user_uid', $uid)
        ->where('event_uid', $authUser->event_uid)
        ->update([
          'interaction_id' => $interaction,
          'is_confirmed' => Interaction::needsConfirmation($interaction)
        ]);
      
      $authUser->refreshInteractions($interaction);

      
      if(in_array($interaction, [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])) {

        $checkHook =  UsersInteraction::where('user_uid', $uid)
                      ->where('interaction_user_uid', $authUser->uid)
                      ->whereIn('interaction_id',  [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
                      ->exists();

        if($checkHook) {

          $type = Notification::TYPE_HOOK;
          $this->publishNotificationForUser($authUser->uid, $type, $authUser->event_uid);

        }else {

          $type = ($interaction == Interaction::LIKE_ID) ? Notification::TYPE_LIKE : Notification::TYPE_SUPER_LIKE;

        }

        $this->publishNotificationForUser($uid, $type, $authUser->event_uid);
        
      }

      DB::commit();

      return [
        "super_like_credits" => $authUser->super_like_credits,
        "like_credits" => $authUser->like_credits,
      ];
      
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new \Exception(__('i18n.set_interaction_ko'));
    }
  }

  private function publishNotificationForUser($userUid, $type, $eventUid)
{
    $notification = [
        'user_uid'  => $userUid,
        'type'      => $type,
        'data'      => "Has obtenido un nuevo $type",
        'read_at'   => null,
        'event_uid' => $eventUid,
    ];

    $this->notificationService->publishNotification($notification);
}
  
}