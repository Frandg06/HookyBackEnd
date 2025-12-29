<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Event;
use App\Models\UserEvent;
use App\Models\UserImage;
use App\Enums\User\GenderEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Enums\User\SexualOrientationEnum;

final class CreateUsesrForEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-event {--uid=} {--count=20} {--gender=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create users for event';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return DB::transaction(function () {
            $eventUid = $this->option('uid');
            if (empty($eventUid)) {
                $this->error('El parámetro --uid es requerido');

                return Command::FAILURE;
            }
            $event = Event::where('uid', $eventUid)->firstOrFail();

            $count = (int) $this->option('count') ?? 20;

            $this->info("Creando {$count} usuarios para el evento: {$event->name}");

            if ($this->option('gender')) {
                $gender = $this->option('gender') === 'female' ? GenderEnum::FEMALE : GenderEnum::MALE;
            }

            $users = User::factory()->count($count)->create([
                'gender' => $gender ?? GenderEnum::MALE,
                'sexual_orientation' => SexualOrientationEnum::HETEROSEXUAL,
            ]);

            $users->each(function (User $user) use ($event) {
                UserEvent::create([
                    'user_uid' => $user->uid,
                    'event_uid' => $event->uid,
                    'logged_at' => now(),
                    'likes' => $event->likes,
                    'super_likes' => $event->super_likes,
                ]);

                if ($user->gender === GenderEnum::MALE) {
                    $rand = rand(1, 99);
                    $image = "https://randomuser.me/api/portraits/men/{$rand}.jpg";
                } else {
                    $rand = rand(1, 99);
                    $image = "https://randomuser.me/api/portraits/women/{$rand}.jpg";
                }
                UserImage::create([
                    'user_uid' => $user->uid,
                    'name' => $image,
                    'order' => 1,
                    'type' => 'profile',
                    'size' => 0,
                ]);
            });

            $this->info("Users created for event: {$eventUid}");

            return Command::SUCCESS;

        });
    }
}
