<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Services\EmailService;
use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ScheduedlEmails implements ShouldQueue
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
        $this->emailService->sendNotifyStartOfEventEmail($this->user, $this->event);
    }
}
