<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteAuthUserRequest;
use App\Http\Requests\CompleteDataRequest;
use App\Http\Resources\TargetUserResource;
use App\Http\Services\AuthUserService;
use App\Http\Services\ImagesService;
use App\Http\Services\NotificationService;
use App\Http\Services\UserService;
use App\Models\TargetUsers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
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
        // todo obtener todas las interacciones del usuario hacia el autenticado y luego filtrar por tipo

        $isLike = TargetUsers::checkIsLike($uid, $user);

        $isSuperlike = TargetUsers::checkIsSuperLike($uid, $user);

        if (! $user->is_premium && ! $isSuperlike) {
            return response()->json([
                'error' => true,
                'message' => __('i18n.not_aviable_user'),
                'redirect' => '/home',
            ], 401);
        }

        if (! $isLike && ! $isSuperlike) {
            return response()->json([
                'error' => true,
                'message' => __('i18n.not_aviable_user'),
                'redirect' => '/home',
            ], 401);
        }

        $user = User::where('uid', $uid)->first();

        return $this->response(TargetUserResource::make($user));
    }

    public function showTargetUser(Request $request, $uid)
    {
        $user = $request->user();

        $isHook = TargetUsers::isHook($user->uid, $uid, $user->event->uid);

        if (! $isHook) {
            return response()->json([
                'error' => true,
                'message' => __('i18n.not_aviable_user'),
                'redirect' => '/home',
            ], 401);
        }

        $user = User::where('uid', $uid)->first();

        return $this->response(TargetUserResource::make($user));
    }

    public function setInteraction(Request $request, $uid)
    {
        $interaction = $request->interactionId;
        $response = $this->userService->setInteraction($uid, $interaction);

        return response()->json(['success' => true, 'resp' => $response], 200);
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

            if (isset($data['userImages' . $i])) {

                $response[] = [
                    'file' => $data['userImages' . $i],
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
