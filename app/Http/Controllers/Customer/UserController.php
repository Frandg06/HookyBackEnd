<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\TargetUsers;
use Illuminate\Http\Request;
use App\Enums\InteractionEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\ImagesService;
use App\Http\Resources\TargetUserResource;

final class UserController extends Controller
{
    protected $authUserService;

    protected $imageService;

    protected $notificationService;

    public function __construct(
        ImagesService $imageService,
    ) {
        $this->imageService = $imageService;
    }

    public function getUserToConfirm(Request $request, $uid)
    {
        $user = $this->user();

        $targetUser = User::find($uid);

        $isConfirm = TargetUsers::where('user_uid', $user->uid)
            ->where('target_user_uid', $uid)
            ->whereIn('interaction', InteractionEnum::LikeInteractions())
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
}
