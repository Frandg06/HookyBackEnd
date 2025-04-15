<?php

namespace App\Console\Commands;

use App\Models\UserImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DestroyImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:destroy-images';

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
        if ($this->confirm('Do you wish to continue?')) {
            foreach (UserImage::all() as $image) {
                $image->delete();
            }
            Storage::disk('r2')->deleteDirectory('hooky/profile');
            Storage::disk('r2')->deleteDirectory('hooky/qr');
        }
        $this->info('Images deleted successfully.');
    }
}
