<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Gender;
use App\Models\Interest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


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

        $sex = rand(1, 2);
        $orient = rand(1, 3);

        for($i = 0; $i < 3000; $i++) {
            User::create([
                'name' => fake()->name(),
                'surnames' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('a'),
                "gender_id" => $sex,
                "sexual_orientation_id" => $orient,
                "role_id" => Role::USER,
                "city" => fake()->city(),
                "born_date" => fake()->date(),
                "ig" => fake()->name(),
                "tw" => fake()->name(),
                "description" => fake()->paragraph(),
            ]);

        }
        

        
        User::all()->each(function ($user)  {
            $randomEvents = Event::inRansomOrder()->first();
            $interests = Interest::inRandomOrder()->limit(3)->pluck('id');
            $user->interestBelongsToMany()->attach($interests);

            UserEvent::create([
              'user_uid' => $user->uid,
              'event_uid' => $randomEvents->uid,
              'logged_at' => now()->format('Y-m-d H:i'),
              'super_likes' => $randomEvents->super_likes,
              'likes' => $randomEvents->likes,
            ]);

            for($i = 0; $i < 3; $i++) {

                $imageData = file_get_contents("https://picsum.photos/500/900");
                
                $img = Image::read($imageData);
    
                $ogWidth = $img->width();
                $ogHeight = $img->height();
                
                $aspectRatio = $ogWidth / $ogHeight;
    
                $newHeight = 500 / $aspectRatio;
    
    
                $processedImage = $img->resize(500, $newHeight)->toWebP(80);
                
                $newImage = $user->userImages()->create([
                  'order' => $user->userImages()->count() + 1,
                  'name' => "databnaseSeeder",
                  'size' => "34886",
                  'type' => "image/png",
                ]);
        
                Storage::disk('r2')->put($newImage->url, $processedImage);
            }

        });
    }
}
