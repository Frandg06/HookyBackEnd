<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Chat;
use App\Models\TargetUsers;
use App\Models\Notification;
use Illuminate\Console\Command;

final class ResetInteractions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-interactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting interactions...');

        // Get all events

        TargetUsers::query()->delete();
        Notification::query()->delete();
        Chat::query()->delete();

        $this->info('Interactions reset successfully.');

    }
}
