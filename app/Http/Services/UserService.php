<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Events\LikeNotificationEvent;
use App\Events\SuperlikeNotificationEvent;
use App\Exceptions\ApiException;
use App\Http\Resources\TargetUserResource;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\NotificationsType;
use App\Models\Notifify;
use App\Models\TargetUsers;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class UserService extends Service
{
    protected $notificationService;

    protected $chatService;

    public function __construct(NotificationService $notificationService, ChatService $chatService)
    {
        $this->notificationService = $notificationService;
        $this->chatService = $chatService;
    }

    public function getTargetUsers()
    {
        DB::beginTransaction();
        try {

            $auth = $this->user();
            $cacheKey = 'target_users_uids_'.$auth->uid.'_'.$auth->event->uid;
            $cachedUids = Cache::get($cacheKey, []);
            $needed = 100 - count($cachedUids);

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

            return TargetUserResource::collection($users);
        } catch (Exception $e) {
            DB::rollBack();
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
            $target_user = User::find($uid);

            TargetUsers::create([
                'user_uid' => $authUid,
                'target_user_uid' => $target_user->uid,
                'event_uid' => $eventUid,
                'interaction_id' => $interaction,
            ]);

            // Actualizo los likes y super likes restantes
            $this->user()->decrementInteraction($interaction);

            // Compruebo si es un hook
            $isHook = TargetUsers::isHook($target_user->uid, $authUid, $eventUid)->exists();

            if ($isHook) {
                $this->handleHook($authUid, $target_user->uid, $eventUid, $chat);
            } elseif ($interaction === Interaction::LIKE_ID) {
                LikeNotificationEvent::dispatch($user, $target_user);
            } elseif ($interaction === Interaction::SUPER_LIKE_ID) {
                SuperlikeNotificationEvent::dispatch($user, $target_user, 'super_like');
            }

            $cacheKey = 'target_users_uids_'.$authUid.'_'.$eventUid;
            $cachedUids = Cache::get($cacheKey, []);
            $filtered = collect($cachedUids)->reject(fn ($cachedUid) => $cachedUid === $target_user->uid)->values();
            Cache::put($cacheKey, $filtered->toArray());

            $response = [
                'super_like_credits' => $this->user()->super_likes,
                'like_credits' => $this->user()->likes,
            ];

            if ($filtered->count() <= 10) {
                $refetch = $this->getTargetUsers();
                if (count($refetch) !== $filtered->count()) {
                    $response['remaining_users'] = $refetch;
                }
            }

            if (isset($chat)) {
                $response['chat'] = $chat;
            }

            DB::commit();

            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function handleHook(string $authUid, string $targetUserUid, string $eventUid, &$chat): void
    {
        $pastNotify = Notification::getLikeAndSuperLikeNotify($authUid, $targetUserUid, $eventUid);

        if ($pastNotify) {
            $pastNotify->delete();
        }

        $chat = $this->chatService->store($authUid, $targetUserUid, $eventUid);

        $notification = new Notifify([
            'reciber_uid' => $targetUserUid,
            'type_id' => NotificationsType::HOOK_TYPE,
            'sender_uid' => $authUid,
            'payload' => [
                'chat_created' => true,
                'chat' => $chat,
            ],
        ]);

        $notification->dualEmitWithSave();
    }

    private function handleLike(int $interaction, string $authUid, string $targetUserUid): void
    {
        $type = $interaction === Interaction::LIKE_ID
            ? NotificationsType::LIKE_TYPE
            : NotificationsType::SUPER_LIKE_TYPE;

        $notification = new Notifify([
            'reciber_uid' => $targetUserUid,
            'type_id' => $type,
            'sender_uid' => $authUid,
        ]);

        $notification->emit();
        $notification->save();
    }
}
