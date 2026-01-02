<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Jobs\ScheduedlEmails;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Models\UserScheduledNotification;

final readonly class NotifyStartOfEventAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, string $eventUid): void
    {
        DB::transaction(function () use ($user, $eventUid) {

            $shouldSend = $user->settings->event_start_email;

            if (! $shouldSend) {
                throw new ApiException('notifications_disabled', 422);
            }

            $event = Event::where('uid', $eventUid)->first();

            $st_date = Carbon::parse($event->st_date);

            $seconds = now()->diffInSeconds($st_date);

            if ($seconds <= 300) {
                throw new ApiException('event_too_soon', 422);
            }

            $scheduledAt = $st_date->copy()->subMinutes(5);

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

            ScheduedlEmails::dispatch($user, $event)->delay($scheduledAt);
        });
    }
}
