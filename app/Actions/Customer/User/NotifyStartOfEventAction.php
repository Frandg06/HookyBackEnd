<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Exceptions\ApiException;
use App\Jobs\ScheduedlEmails;
use App\Models\Event;
use App\Models\User;
use App\Models\UserScheduledNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final readonly class NotifyStartOfEventAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, string $eventUid): void
    {
        DB::transaction(function () use ($user, $eventUid) {
            $event = Event::where('uid', $eventUid)->first();

            $scheduledAt = Carbon::parse($event->st_date)->subMinutes(5);

            $seconds = now()->diffInSeconds($scheduledAt);

            $notification = UserScheduledNotification::firstOrCreate(
                [
                    'user_uid' => $user->uid,
                    'event_uid' => $event->uid,
                ],
                [
                    'scheduled_at' => $scheduledAt,
                    'status' => 'pending',
                ]
            );

            if (! $notification->wasRecentlyCreated) {
                throw new ApiException('notification_already_scheduled', 422);
            }

            ScheduedlEmails::dispatch($user, $event)->delay($seconds);

        });
    }
}
