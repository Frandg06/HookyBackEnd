<?php
namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\UserResource;
use App\Http\Services\NotificationService;
use App\Http\Services\WsChatService;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\NotificationsType;
use App\Models\SexualOrientation;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
  protected $notificationService, $wsChatService;

  public function __construct(NotificationService $notificationService ,WsChatService $wsChatService) {
    $this->notificationService = $notificationService;
    $this->wsChatService = $wsChatService;
  }

  public function getUsers() {
    DB::beginTransaction();
    try {

      
      $authUser = request()->user();
      Log::info("authUser->uid: " . $authUser->uid);
      // usuarios ya obtenidos previamente con lo que no se ha interactuado en el evento actual
      $usersWithoutInteraction = $authUser->interactions()->usersWithoutInteraction($authUser->event_uid);
      
      // usuarios que se han cargado previamente y que se ha interactuado en el evento actual
      $usersWithInteraction = $authUser->interactions()->usersWithInteraction($authUser->event_uid);

      // obtener los usuarios que se van a interactuar que esten en el evento que no se haya interactuado con ellos
      if($authUser->sexual_orientation_id == SexualOrientation::BISEXUAL) {
        $users = User::getBisexualUsersToInteract($authUser, $usersWithInteraction, $usersWithoutInteraction);
      }else {
        $users = User::getUsersToInteract($authUser, $usersWithInteraction, $usersWithoutInteraction);
      }
      Log::info("users: " . count($users));
      $newUsersWithInteractions = [];

      foreach ($users as $userToInsert) {  
        if(UsersInteraction::where('user_uid', $authUser->uid)->where('interaction_user_uid', $userToInsert->uid)->count() > 0) continue;
      
        $newUsersWithInteractions[] = [
          'user_uid' => $authUser->uid,
          'interaction_user_uid' => $userToInsert->uid,
          'interaction_id' => null,
          'event_uid' => $authUser->event_uid,
          'created_at' => now()
        ];
      }

      UsersInteraction::insert($newUsersWithInteractions);
      
      DB::commit();

      return UserResource::collection($users);

    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new ApiException('get_users_ko', 500);
    }
  }

  public function setInteraction($uid, $interaction) {
    DB::beginTransaction();
    try {
      $authUser = request()->user();
      
      $search = UsersInteraction::where('user_uid', $authUser->uid)
        ->where('interaction_user_uid', $uid)
        ->where('event_uid', $authUser->event_uid)
        ->first();

      if(!empty($search)) {
        $search->update(['interaction_id' => $interaction]);
      }else {
        UsersInteraction::create([
          'user_uid' => $authUser->uid,
          'interaction_user_uid' => $uid,
          'interaction_id' => $interaction,
          'event_uid' => $authUser->event_uid
        ]);
      }
      
      $authUser->refreshInteractions($interaction);
      
      $checkHook =  UsersInteraction::checkHook($uid, $authUser->uid, $authUser->event_uid);

      if($checkHook) {
        $existLike = Notification::getLikeAndSuperLikeNotify($authUser->uid, $uid, $authUser->event_uid);
        if($existLike) $existLike->delete();

        $type = NotificationsType::HOOK_TYPE;
        $type_str = NotificationsType::HOOK_TYPE_STR;

        $this->publishNotificationForUser($authUser->uid, $uid, $authUser->event_uid, $type, $type_str);
        $this->publishNotificationForUser($uid, $authUser->uid, $authUser->event_uid, $type, $type_str);

        $this->wsChatService->storeChat($authUser->uid, $uid, $authUser->event_uid);

      }elseif(in_array($interaction, [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])) {
        
        $type = ($interaction == Interaction::LIKE_ID) ? NotificationsType::LIKE_TYPE : NotificationsType::SUPER_LIKE_TYPE;
        $type_str = ($interaction == Interaction::LIKE_ID) ? NotificationsType::LIKE_TYPE_STR : NotificationsType::SUPER_LIKE_TYPE_STR;
        $this->publishNotificationForUser($uid, $authUser->uid, $authUser->event_uid, $type, $type_str);

      }

      $remainingUsers = $authUser->remainingUsersToInteract();
      $remainingUsersCount = $remainingUsers->count();
      
      $response = [
        "super_like_credits" => $authUser->super_like_credits,
        "like_credits" => $authUser->like_credits,
      ];

      if($remainingUsersCount <= 10) {
        $refetch = $this->getUsers();
        if(count($refetch) != $remainingUsersCount) {
          $response['remaining_users'] = $refetch;
        }
      }
      
      DB::commit();

      return $response;
      
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new ApiException('set_interaction_ko', 500); 
    }
  }

  private function publishNotificationForUser($reciber, $emiter, $event, $type, $type_str)
  {
      $notification = [
          'user_uid'    => $reciber,
          'emitter_uid' => $emiter,
          'event_uid'   => $event,
          'type_id'     => $type,
          'type_str'    => $type_str,
          'read_at'     => null,
      ];

      $this->notificationService->publishNotification($notification);
  }
  
}