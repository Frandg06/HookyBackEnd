<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

final class CreateEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-events {--count=10}';

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
        $count = (int) $this->option('count');

        Event::factory()->count($count)->create([
            'name' => 'Sample Event',
        ]);
    }
}
