<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Services\EmailService;
use App\Models\Event;
use App\Models\User;
use App\Models\UserScheduledNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ScheduedlEmails implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private EmailService $emailService;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly User $user,
        private readonly Event $event
    ) {
        $this->emailService = new EmailService();
    }

    /**
     * Recibe los SERVICIOS aquí. Laravel los inyecta automáticamente al procesar.
     */
    public function handle(): void
    {
        UserScheduledNotification::where('user_uid', $this->user->id)
            ->where('event_uid', $this->event->uid)
            ->update(['status' => 'sent']);

        $this->emailService->sendNotifyStartOfEventEmail($this->user, $this->event);
    }

    public function uniqueId()
    {
        return 'user_'.$this->user->uid.'_event_'.$this->event->uid;
    }

    public function uniqueFor()
    {
        return 60 * 60 * 24 * 365;
    }
}
