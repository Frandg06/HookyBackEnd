<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\TargetUsers;
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
}
