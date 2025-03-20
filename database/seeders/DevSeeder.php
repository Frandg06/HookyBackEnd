<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Gender;
use App\Models\Interest;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;


class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(env('APP_ENV') !== 'local') return;

        $this->call(EventsMockSeeder::class);
        $this->call(UserMockSeeder::class);

        Event::factory()->count(93)->create();

        Event::all()->each(function ($event)  {

          $userCount = rand(20, 50);

          $tickets = [];  
            $count = fake()->numberBetween(1, 300);
            while (count($tickets) < $count) {
                $code = strtoupper(Str::random(6));

                $tickets[] = [
                    'uid' => (string) Str::uuid(), 
                    'company_uid' => $event->company->uid,
                    'event_uid' => $event->uid,
                    'code' => $code,
                    'redeemed' => true,
                    'likes' => fake()->numberBetween(1, 100),
                    'super_likes' => fake()->numberBetween(1, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Ticket::insert($tickets);

          User::factory()->count($userCount)->create()->each(function($user) use ($event) {
                UserEvent::create([
                  'user_uid' => $user->uid,
                  'event_uid' => $event->uid,
                  'logged_at' => fake()->dateTimeInInterval('-12 hours', '+15 hours'),  
                  'super_likes' => $event->super_likes,
                  'likes' => $event->likes,
                ]);
          });

        
        });

        
        
        
        // User::all()->each(function ($user)  {
        //     $randomEvents = Event::inRandomOrder()->first();
        //     $interests = Interest::inRandomOrder()->limit(3)->pluck('id');
        //     $user->interestBelongsToMany()->attach($interests);


            // for($i = 0; $i < 3; $i++) {

            //     $imageData = file_get_contents("https://picsum.photos/500/900");
                
            //     $img = Image::read($imageData);
    
            //     $ogWidth = $img->width();
            //     $ogHeight = $img->height();
                
            //     $aspectRatio = $ogWidth / $ogHeight;
    
            //     $newHeight = 500 / $aspectRatio;
    
    
            //     $processedImage = $img->resize(500, $newHeight)->toWebP(80);
                
            //     $newImage = $user->userImages()->create([
            //       'order' => $user->userImages()->count() + 1,
            //       'name' => "databnaseSeeder",
            //       'size' => "34886",
            //       'type' => "image/png",
            //     ]);
        
            //     Storage::disk('r2')->put($newImage->url, $processedImage);
            // }

        // });
    }
}
