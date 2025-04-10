<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\UserResource;
use App\Http\Services\NotificationService;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\NotificationsType;
use App\Models\Notifify;
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
            ]);

            // Actualizo los likes y super likes restantes
            $this->user()->refreshCredits($interaction);

            // Compruebo si es un hook
            $isHook =  UsersInteraction::isHook($targetUserUid, $authUid, $eventUid)->exists();

            if ($isHook) {
                $this->handleHook($authUid, $targetUserUid, $eventUid, $chat);
            } elseif (in_array($interaction, [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])) {
                $this->handleLike($interaction, $authUid, $targetUserUid);
            }

            $cacheKey = 'target_users_uids_' . $authUid . '_' .  $eventUid;
            $cachedUids = Cache::get($cacheKey, []);
            $filtered = collect($cachedUids)->reject(fn($cachedUid) => $cachedUid == $targetUserUid)->values();
            Cache::put($cacheKey, $filtered->toArray());

            $response = [
                'super_like_credits' => $this->user()->super_likes,
                'like_credits' => $this->user()->likes,
            ];

            if ($filtered->count() <= 10) {
                $refetch = $this->getUsers();
                if (count($refetch) != $filtered->count()) {
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

    private function handleHook(string $authUid, string $targetUserUid, string $eventUid, &$chat): void
    {
        $pastNotify = Notification::getLikeAndSuperLikeNotify($authUid, $targetUserUid, $eventUid);
        if ($pastNotify) {
            $pastNotify->delete();
        }


        $notification = new Notifify([
            'reciber_uid' => $targetUserUid,
            'type_id' => NotificationsType::HOOK_TYPE,
            'sender_uid' => $authUid,
            'payload' => ['chat_created' => true]
        ]);

        $notification->dualEmitWithSave();

        $chat = $this->chatService->store($authUid, $targetUserUid, $eventUid);
    }

    private function handleLike(int $interaction, string $authUid, string $targetUserUid): void
    {
        $type = $interaction === Interaction::LIKE_ID
            ? NotificationsType::LIKE_TYPE
            : NotificationsType::SUPER_LIKE_TYPE;

        $notification = new Notifify([
            'reciber_uid' => $targetUserUid,
            'type_id' => $type,
            'sender_uid' => $authUid
        ]);

        $notification->emit();
        $notification->save();
    }
}
