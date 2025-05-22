<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Gender;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // if (app()->isLocal()) {

        $this->call(EventsMockSeeder::class);
        $this->call(UserMockSeeder::class);

        Event::factory()->count(10)->create();

        DB::table('events')
            ->update([
                'end_date' => DB::raw("st_date + INTERVAL '5 hours'")
            ]);

        Event::all()->each(function ($event) {

            $tickets = [];
            $ticketCount = rand(20, 50);

            while (count($tickets) < $ticketCount) {
                $code = strtoupper(Str::random(6));

                $tickets[] = [
                    'uid' => (string) Str::uuid(),
                    'company_uid' => $event->company->uid,
                    'event_uid' => $event->uid,
                    'code' => $code,
                    'redeemed' => true,
                    'price' => rand(3, 6),
                    'redeemed_at' => fake()->dateTimeInInterval('-6 months', '+6 months'),
                    'likes' => fake()->numberBetween(1, 100),
                    'super_likes' => fake()->numberBetween(1, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Ticket::insert($tickets);

            $userCount = rand(20, 50);

            User::factory()->count($userCount)->create()->each(function ($user) use ($event) {
                UserEvent::create([
                    'user_uid' => $user->uid,
                    'event_uid' => $event->uid,
                    'logged_at' => fake()->dateTimeInInterval('-12 hours', '+15 hours'),
                    'super_likes' => $event->super_likes,
                    'likes' => $event->likes,
                ]);

                // $imageData = $this->downloadImageWithRetries('https://picsum.photos/200/300');

                // $img = Image::read($imageData);

                // $ogWidth = $img->width();
                // $ogHeight = $img->height();

                // $aspectRatio = $ogWidth / $ogHeight;

                // $newHeight = 500 / $aspectRatio;

                // $processedImage = $img->resize(500, $newHeight)->toWebP(80);

                // $newImage = $user->userImages()->create([
                //     'order' => $user->userImages()->count() + 1,
                //     'name' => 'databnaseSeeder',
                //     'size' => '34886',
                //     'type' => 'image/png',
                // ]);

                // Storage::disk('r2')->put($newImage->url, $processedImage);
            });
        });



        // User::all()->each(function ($user) use ($event) {
        //     UserEvent::create([
        //         'user_uid' => $user->uid,
        //         'event_uid' => $event->uid,
        //         'logged_at' => fake()->dateTimeInInterval('-12 hours', '+15 hours'),
        //         'super_likes' => $event->super_likes,
        //         'likes' => $event->likes,
        //     ]);

        //     for ($i = 0; $i < 3; $i++) {

        //         $imageData = file_get_contents('https://picsum.photos/500/900');

        //         $img = Image::read($imageData);

        //         $ogWidth = $img->width();
        //         $ogHeight = $img->height();

        //         $aspectRatio = $ogWidth / $ogHeight;

        //         $newHeight = 500 / $aspectRatio;

        //         $processedImage = $img->resize(500, $newHeight)->toWebP(80);

        //         $newImage = $user->userImages()->create([
        //             'order' => $user->userImages()->count() + 1,
        //             'name' => 'databnaseSeeder',
        //             'size' => '34886',
        //             'type' => 'image/png',
        //         ]);

        //         Storage::disk('r2')->put($newImage->url, $processedImage);
        //     }
        // });
        // }
    }

    public function downloadImageWithRetries($url, $maxRetries = 10, $delayMs = 2000)
    {
        $attempts = 0;
        while ($attempts < $maxRetries) {
            try {
                $imageData = file_get_contents($url);
                if ($imageData !== false) {
                    return $imageData;
                }
            } catch (\Exception $e) {
                // Ignora la excepción, intenta de nuevo
            }

            $attempts++;
            usleep($delayMs * 1000); // espera entre intentos (en milisegundos)
        }

        throw new \Exception("No se pudo descargar la imagen después de $maxRetries intentos.");
    }
}
