<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\UserResource;
use App\Http\Services\NotificationService;
use App\Http\Services\WsChatService;
use App\Models\Gender;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\NotificationsType;
use App\Models\Notifify;
use App\Models\SexualOrientation;
use App\Models\User;
use App\Models\UsersInteraction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService extends Service
{
    protected $notificationService;
    protected $chatService;

    public function __construct(NotificationService $notificationService, ChatService $chatService)
    {
        $this->notificationService = $notificationService;
        $this->chatService = $chatService;
    }

    public function getUsers()
    {
        DB::beginTransaction();
        try {

            $auth = $this->user();
            $cacheKey = 'target_users_uids_' . $auth->uid . '_' .  $auth->event->uid;
            $cachedUids = Cache::get($cacheKey, []);
            $needed = 50 - count($cachedUids);

            if ($needed > 0) {
                $targetUsers = User::whereTargetUsersFrom($auth)
                    ->whereNotIn('uid', $cachedUids)
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc')
                    ->limit($needed)
                    ->get();

                $targetUids = $targetUsers->pluck('uid')->toArray();

                $cachedUids = array_merge($cachedUids, $targetUids);
                Cache::put($cacheKey, $cachedUids);
            }

            $users = User::whereIn('uid', $cachedUids)->get();
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
            $authUid = $user->uid;
            $targetUserUid = $uid;

            UsersInteraction::create([
                'user_uid' => $authUid,
                'interaction_user_uid' => $targetUserUid,
                'event_uid' => $eventUid,
                'interaction_id' => $interaction
            ],);
            // Actualizo los likes y super likes restantes
            $this->user()->refreshCredits($interaction);

            // Compruebo si es un hook
            $checkHook =  UsersInteraction::checkHook($uid, $authUid, $eventUid);

            if ($checkHook) {
                // Si lo es y existe la interacción como like la elimino
                $existLike = Notification::getLikeAndSuperLikeNotify($authUid, $uid, $eventUid);
                if ($existLike) {
                    $existLike->delete();
                }

                $type = NotificationsType::HOOK_TYPE;

                $notify = new Notifify([
                    'reciber_uid' => $uid,
                    'type_id' => $type,
                    'sender_uid' => $authUid,
                    'payload' => [
                        'chat_created' => true,
                    ]
                ]);

                $notify->dualEmitWithSave();

                // Creo el chat
                $chat = $this->chatService->store($authUid, $uid, $eventUid);
            } elseif (in_array($interaction, [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])) {
                $isLike = $interaction == Interaction::LIKE_ID;

                $type = $isLike ? NotificationsType::LIKE_TYPE : NotificationsType::SUPER_LIKE_TYPE;

                $notify = new Notifify([
                    'reciber_uid' => $uid,
                    'type_id' => $type,
                    'sender_uid' => $authUid,

                ]);

                $notify->emit();
                $notify->save();
            }

            $remainingUsers = $this->user()->remainingUsersToInteract();
            $remainingUsersCount = $remainingUsers->count();

            $response = [
                'super_like_credits' => $this->user()->super_likes,
                'like_credits' => $this->user()->likes,
            ];

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
}
