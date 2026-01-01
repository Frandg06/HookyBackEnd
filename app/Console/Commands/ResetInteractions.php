<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Chat;
use App\Models\User;
use App\Models\TargetUsers;
use App\Models\Notification;
use App\Enums\InteractionEnum;
use App\Enums\User\GenderEnum;
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

        // TargetUsers::query()->delete();
        // Notification::query()->delete();
        // Chat::query()->delete();

        $users = User::where('gender', GenderEnum::FEMALE)->get();
        foreach ($users as $user) {
            TargetUsers::create([
                'user_uid' => $user->uid,
                'target_user_uid' => '019b7aa4-b4c9-71e6-b6a6-5069bc691a49',
                'interaction' => InteractionEnum::LIKE,
                'event_uid' => '9c1998d3-0158-3bd9-bbb8-64544685bbdc',
            ]);
            TargetUsers::create([
                'user_uid' => '019b7aa4-b4c9-71e6-b6a6-5069bc691a49',
                'target_user_uid' => $user->uid,
                'interaction' => InteractionEnum::LIKE,
                'event_uid' => '9c1998d3-0158-3bd9-bbb8-64544685bbdc',
            ]);
        }
        $this->info('Interactions reset successfully.');

    }
}
