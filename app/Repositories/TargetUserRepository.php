<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Hook;
use App\Models\User;
use App\Models\TargetUsers;
use App\Dtos\InteractionDto;
use App\Enums\InteractionEnum;
use App\Enums\User\GenderEnum;
use App\Enums\User\SexualOrientationEnum;
use Illuminate\Database\Eloquent\Collection;

final class TargetUserRepository
{
    public function create(array $data): TargetUsers
    {
        return TargetUsers::create($data);
    }

    public function getTargetUsers(User $user): Collection
    {
        return User::with('images')
            ->whereNot('uid', $user->uid)
            ->has('images', '>', 0)
            ->when($user->sexual_orientation->isHomosexual(), function ($q) use ($user) {
                $q->where('gender', $user->gender->same())
                    ->whereIn('sexual_orientation', [$user->sexual_orientation, SexualOrientationEnum::BISEXUAL]);
            })
            ->when($user->sexual_orientation->isHeterosexual(), function ($q) use ($user) {
                $q->where('gender', $user->gender->opposite())
                    ->whereIn('sexual_orientation', [$user->sexual_orientation, SexualOrientationEnum::BISEXUAL]);
            })
            ->when($user->sexual_orientation->isBisexual(), function ($q) use ($user) {
                $q->when($user->gender->isMale(), function ($query) {
                    $query->where(function ($q) {
                        $q->where(function ($subQuery) {
                            $subQuery->where('gender', GenderEnum::MALE)
                                ->whereIn('sexual_orientation', [SexualOrientationEnum::GAY, SexualOrientationEnum::BISEXUAL]);
                        })->orWhere(function ($subQuery) {
                            $subQuery->where('gender', GenderEnum::FEMALE)
                                ->whereIn('sexual_orientation', [SexualOrientationEnum::HETEROSEXUAL, SexualOrientationEnum::BISEXUAL]);
                        });
                    });
                });
                $q->when($user->gender->isFemale(), function ($query) {
                    $query->where(function ($q) {
                        $q->where(function ($subQuery) {
                            $subQuery->where('gender', GenderEnum::FEMALE)
                                ->whereIn('sexual_orientation', [SexualOrientationEnum::LESBIAN, SexualOrientationEnum::BISEXUAL]);
                        })->orWhere(function ($subQuery) {
                            $subQuery->where('gender', GenderEnum::MALE)
                                ->whereIn('sexual_orientation', [SexualOrientationEnum::HETEROSEXUAL, SexualOrientationEnum::BISEXUAL]);
                        });
                    });
                });
            })
            ->whereHas('events', function ($q) use ($user) {
                $q->where('event_uid', $user->event->uid);
            })
            ->whereNotIn('uid', function ($q) use ($user) {
                $q->select('target_user_uid')
                    ->from('target_users as ui')
                    ->where('ui.user_uid', $user->uid)
                    ->where('ui.event_uid', $user->event->uid);
            })
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->limit(100)
            ->get();
    }

    public function getTargetUsersFromUids(array $uids): Collection
    {
        return User::with('images')->whereIn('uid', $uids)->get();
    }

    public function isHook(InteractionDto $dto): bool
    {
        return Hook::where(function ($query) use ($dto) {
            $query->where('user1_uid', $dto->user_uid)
                ->where('user2_uid', $dto->target_user_uid);
        })->orWhere(function ($query) use ($dto) {
            $query->where('user1_uid', $dto->target_user_uid)
                ->where('user2_uid', $dto->user_uid);
        })
            ->where('event_uid', $dto->event_uid)
            ->exists();
    }

    public function hasUserInteractedWith(string $userUid, string $targetUserUid, string $eventUid): bool
    {
        return TargetUsers::where('user_uid', $userUid)
            ->where('target_user_uid', $targetUserUid)
            ->whereIn('interaction', InteractionEnum::LikeInteractions())
            ->where('event_uid', $eventUid)
            ->exists();
    }

    public function hasReceivedLikeFrom(string $targetUserUid, string $userUid, string $eventUid): bool
    {
        return TargetUsers::where('user_uid', $targetUserUid)
            ->where('target_user_uid', $userUid)
            ->where('interaction', InteractionEnum::LIKE)
            ->where('event_uid', $eventUid)
            ->exists();
    }

    public function hasReceivedSuperlikeFrom(string $targetUserUid, string $userUid, string $eventUid): bool
    {
        return TargetUsers::where('user_uid', $targetUserUid)
            ->where('target_user_uid', $userUid)
            ->where('interaction', InteractionEnum::SUPERLIKE)
            ->where('event_uid', $eventUid)
            ->exists();
    }

    public function getInteractionsHistory(User $user, int $page = 1)
    {
        return TargetUsers::with(['emitter:uid,name,born_date', 'emitter.images:uid,user_uid'])
            ->where('target_user_uid', $user->uid)
            ->where('event_uid', $user->event->uid)
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'page', $page);
    }

    public function getInteractionsGivenHistory(User $user, int $page = 1)
    {
        return TargetUsers::with(['targetUser:uid,name,born_date', 'targetUser.images:uid,user_uid'])
            ->where('user_uid', $user->uid)
            ->where('event_uid', $user->event->uid)
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'page', $page);
    }
}
