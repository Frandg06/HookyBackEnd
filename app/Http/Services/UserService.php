<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\UserResource;
use App\Http\Services\NotificationService;
use App\Http\Services\WsChatService;
use App\Models\Chat;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\NotificationsType;
use App\Models\SexualOrientation;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService extends Service
{
    protected $notificationService;
    protected $wsChatService;
    protected $chatService;

    public function __construct(NotificationService $notificationService, WsChatService $wsChatService, ChatService $chatService)
    {
        $this->notificationService = $notificationService;
        $this->wsChatService = $wsChatService;
        $this->chatService = $chatService;
    }

    public function getUsers()
    {
        DB::beginTransaction();
        try {
            $authUser = $this->user();
            Log::info('authUser->uid: ' . $authUser->uid);
            // usuarios ya obtenidos previamente con lo que no se ha interactuado en el evento actual
            $usersWithoutInteraction = $authUser->interactions()->usersWithoutInteraction($authUser->event->uid);

            // usuarios que se han cargado previamente y que se ha interactuado en el evento actual
            $usersWithInteraction = $authUser->interactions()->usersWithInteraction($authUser->event->uid);

            // obtener los usuarios que se van a interactuar que esten en el evento que no se haya interactuado con ellos
            if ($authUser->sexual_orientation_id == SexualOrientation::BISEXUAL) {
                $users = User::getBisexualUsersToInteract($authUser, $usersWithInteraction, $usersWithoutInteraction);
            } else {
                $users = User::getUsersToInteract($authUser, $usersWithInteraction, $usersWithoutInteraction);
            }

            $newUsersWithInteractions = [];

            foreach ($users as $userToInsert) {
                if (UsersInteraction::where('user_uid', $authUser->uid)->where('interaction_user_uid', $userToInsert->uid)->count() > 0) {
                    continue;
                }

                $newUsersWithInteractions[] = [
                    'user_uid' => $authUser->uid,
                    'interaction_user_uid' => $userToInsert->uid,
                    'interaction_id' => null,
                    'event_uid' => $authUser->event->uid,
                    'created_at' => now()
                ];
            }

            UsersInteraction::insert($newUsersWithInteractions);

            DB::commit();

            return UserResource::collection($users);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('get_users_ko', 500);
        }
    }

    public function setInteraction($uid, $interaction)
    {
        DB::beginTransaction();
        try {

            $user = $this->user();
            $eventUid = $user->event->uid;
            $userUid = $user->uid;
            // Busco si previamente se habia creado la plantilla de interacciones
            UsersInteraction::updateOrCreate(
                [
                    'user_uid' => $userUid,
                    'interaction_user_uid' => $uid,
                    'event_uid' => $eventUid
                ],
                [
                    'interaction_id' => $interaction
                ]
            );
            // Actualizo los likes y super likes restantes
            $this->user()->refreshInteractions($interaction);

            // Compruebo si es un hook
            $checkHook =  UsersInteraction::checkHook($uid, $userUid, $eventUid);

            if ($checkHook) {
                // Si lo es y existe la interacción como like la elimino
                $existLike = Notification::getLikeAndSuperLikeNotify($userUid, $uid, $eventUid);
                if ($existLike) {
                    $existLike->delete();
                }

                $type = NotificationsType::HOOK_TYPE;
                $type_str = NotificationsType::HOOK_TYPE_STR;

                $this->publishNotificationForUser($userUid, $uid, $eventUid, $type, $type_str);
                $this->publishNotificationForUser($uid, $userUid, $eventUid, $type, $type_str);

                // Creo el chat 
                $chat = $this->chatService->store($userUid, $uid, $eventUid);
            } elseif (in_array($interaction, [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])) {
                $isLike = $interaction == Interaction::LIKE_ID;

                $type = $isLike ? NotificationsType::LIKE_TYPE : NotificationsType::SUPER_LIKE_TYPE;
                $type_str = $isLike ? NotificationsType::LIKE_TYPE_STR : NotificationsType::SUPER_LIKE_TYPE_STR;

                $this->publishNotificationForUser($uid, $userUid, $eventUid, $type, $type_str);
            }

            $remainingUsers = $this->user()->remainingUsersToInteract();
            $remainingUsersCount = $remainingUsers->count();

            $response = [
                'super_like_credits' => $this->user()->super_likes,
                'like_credits' => $this->user()->likes,
            ];

            Log::alert($response);

            if ($remainingUsersCount <= 10) {
                $refetch = $this->getUsers();
                if (count($refetch) != $remainingUsersCount) {
                    $response['remaining_users'] = $refetch;
                }
            }

            if (isset($chat)) {
                $response['chat'] = $chat;
            }

            DB::commit();

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
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
