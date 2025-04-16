<?php

namespace App\Console\Commands;

use App\Models\Chat;
use App\Models\Notification;
use App\Models\TargetUsers;
use Illuminate\Console\Command;
use PHPUnit\Framework\TestStatus\Notice;

class ResetInteractions extends Command
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
