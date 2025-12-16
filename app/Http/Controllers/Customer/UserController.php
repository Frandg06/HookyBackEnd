<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\DTO\InteractionDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteAuthUserRequest;
use App\Http\Requests\CompleteDataRequest;
use App\Http\Resources\TargetUserResource;
use App\Http\Services\AuthUserService;
use App\Http\Services\ImagesService;
use App\Http\Services\NotificationService;
use App\Http\Services\UserService;
use App\Models\Interaction;
use App\Models\TargetUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function update(CompleteDataRequest $request)
    {
        $data = $request->all();
        $response = $this->authUserService->update($data);

        return response()->json(['success' => true, 'resp' => $response], 200);
    }

    public function completeRegisterData(CompleteAuthUserRequest $request)
    {
        $data = $request->all();
        $info = $this->parseCompleteData($data);
        $files = $this->parseCompleteFiles($data);

        $response = $this->authUserService->completeRegisterData($info, $files);

        return response()->json(['success' => true, 'resp' => $response], 200);
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
            'gender_id' => $data['gender_id'],
            'name' => $data['name'],
            'sexual_orientation_id' => $data['sexual_orientation_id'],
            'surnames' => $data['surnames'],
        ];
    }

    private function parseCompleteFiles($data)
    {
        $size = json_decode($data['userImagesSizes'], true);

        $response = [];
        for ($i = 0; $i < 3; $i++) {

            if (isset($data['userImages'.$i])) {

                $response[] = [
                    'file' => $data['userImages'.$i],
                    'data' => [
                        'width' => $size[$i]['width'],
                        'height' => $size[$i]['height'],
                    ],
                ];
            }
        }

        return $response;
    }
}
