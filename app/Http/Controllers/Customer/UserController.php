<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\DTO\InteractionDto;
use App\Models\Interaction;
use App\Models\TargetUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Services\ImagesService;
use App\Http\Services\AuthUserService;
use App\Http\Resources\TargetUserResource;
use App\Http\Services\NotificationService;

final class UserController extends Controller
{
    protected $authUserService;

    protected $userService;

    protected $imageService;

    protected $notificationService;

    public function __construct(
        AuthUserService $authUserService,
        UserService $userService,
        ImagesService $imageService,
        NotificationService $notificationService
    ) {
        $this->authUserService = $authUserService;
        $this->userService = $userService;
        $this->imageService = $imageService;
        $this->notificationService = $notificationService;
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'old_password' => [Rule::requiredIf(fn () => user()->auto_password === false)],
        ]);

        $data = $request->only('old_password', 'password');
        $response = $this->authUserService->updatePassword($data);

        return response()->json(['success' => true, 'resp' => $response], 200);
    }

    public function getNotifications()
    {
        $response = $this->authUserService->getNotifications();

        return response()->json(['success' => true, 'resp' => $response], 200);
    }

    public function readNotificationsByType($type)
    {
        $response = $this->notificationService->readNotificationsByType($type);

        return response()->json(['success' => true, 'resp' => $response], 200);
    }

    public function retrieveTargetUsers()
    {
        $response = $this->userService->getTargetUsers();

        return response()->json(['resp' => $response, 'success' => true], 200);
    }

    public function getUserToConfirm(Request $request, $uid)
    {
        $user = $this->user();

        $targetUser = User::find($uid);

        $isConfirm = TargetUsers::where('user_uid', $user->uid)
            ->where('target_user_uid', $uid)
            ->whereIn('interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
            ->where('event_uid', $user->event->uid)
            ->exists();

        if ($isConfirm) {
            return $this->successResponse('User to confirm retrieved', [
                'user' => TargetUserResource::make($targetUser),
                'to_confirm' => false,
            ]);
        }

        $isLike = TargetUsers::checkIsLike($uid, $user);

        $isSuperlike = TargetUsers::checkIsSuperLike($uid, $user);

        if (! $user->is_premium && ! $isSuperlike) {
            return response()->json([
                'error' => true,
                'message' => __('i18n.not_aviable_user'),
                'redirect' => '/home',
            ], 403);
        }

        if (! $isLike && ! $isSuperlike) {
            return response()->json([
                'error' => true,
                'message' => __('i18n.not_aviable_user'),
                'redirect' => '/home',
            ], 403);
        }

        return $this->successResponse('User to confirm retrieved', [
            'user' => TargetUserResource::make($targetUser),
            'to_confirm' => true,
        ]);
    }

    public function showTargetUser(Request $request, $uid)
    {
        $user = $request->user();

        $dto = InteractionDto::fromArray([
            'user_uid' => $user->uid,
            'target_user_uid' => $uid,
            'event_uid' => $user->event->uid,
        ]);

        $isHook = TargetUsers::isHook($dto);

        if (! $isHook) {
            return response()->json([
                'error' => true,
                'message' => __('i18n.not_aviable_user'),
                'redirect' => '/home',
            ], 401);
        }

        $user = User::where('uid', $uid)->first();

        return $this->successResponse('User to confirm retrieved', [
            'user' => TargetUserResource::make($user),
            'to_confirm' => false,
        ]);
    }

    private function parseCompleteData($data)
    {
        return [
            'born_date' => $data['born_date'],
            'description' => $data['description'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'name' => $data['name'],
            'sexual_orientation' => $data['sexual_orientation'],
            'surnames' => $data['surnames'],
        ];
    }
}
