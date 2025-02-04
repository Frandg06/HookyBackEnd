<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Services\NotificationService;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\NotificationsType;
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

      $search = UsersInteraction::where('user_uid', $authUser->uid)
        ->where('interaction_user_uid', $uid)
        ->where('event_uid', $authUser->event_uid)
        ->first();

      if(!empty($search)) {
        $search->update([
          'interaction_id' => $interaction,
          'is_confirmed' => Interaction::needsConfirmation($interaction)
        ]);
      }else {
        UsersInteraction::create([
          'user_uid' => $authUser->uid,
          'interaction_user_uid' => $uid,
          'interaction_id' => $interaction,
          'is_confirmed' => Interaction::needsConfirmation($interaction),
          'event_uid' => $authUser->event_uid
        ]);
      }
      
      $authUser->refreshInteractions($interaction);
      
      $checkHook =  UsersInteraction::checkHook($uid, $authUser->uid, $authUser->event_uid);

      if($checkHook) {

        $existLike = Notification::getLikeAndSuperLikeNotify($authUser->uid, $uid, $authUser->event_uid);
        if($existLike) $existLike->delete();

        $type = NotificationsType::HOOK_TYPE;
        $msg = NotificationsType::HOOK_TYPE_STR;

        $this->publishNotificationForUser($authUser->uid, $uid, $authUser->event_uid, $type, $msg);
        $this->publishNotificationForUser($uid, $authUser->uid, $authUser->event_uid, $type, $msg);

      }elseif(in_array($interaction, [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])) {
        
        $type = ($interaction == Interaction::LIKE_ID) ? NotificationsType::LIKE_TYPE : NotificationsType::SUPER_LIKE_TYPE;
        $msg = ($interaction == Interaction::LIKE_ID) ? NotificationsType::LIKE_TYPE_STR : NotificationsType::SUPER_LIKE_TYPE_STR;
        $this->publishNotificationForUser($uid, $authUser->uid, $authUser->event_uid, $type, $msg);

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

  private function publishNotificationForUser($reciber, $emiter, $event, $type, $msg)
{
    $notification = [
        'user_uid'    => $reciber,
        'emitter_uid' => $emiter,
        'event_uid'   => $event,
        'type_id'     => $type,
        'msg'         => 'notification.'.$msg,
        'read_at'     => null,
    ];

    $this->notificationService->publishNotification($notification);
}
  
}