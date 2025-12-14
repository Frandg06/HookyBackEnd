<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Exceptions\ApiException;
use App\Jobs\ScheduedlEmails;
use App\Models\Event;
use App\Models\User;
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

            if (! $event) {
                throw new ApiException('event_not_found', 404);
            }

            $scheduledAt = now()->diffInSeconds(Carbon::parse($event->st_date)->subMinutes(5));

            ScheduedlEmails::dispatch($user, $event)->delay($scheduledAt);

        });
    }
}
