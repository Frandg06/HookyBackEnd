<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Event;
use App\Models\Gender;
use App\Models\Interest;
use App\Models\Role;
use App\Models\SexualOrientation;
use App\Models\TimeZone;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Storage::disk('r2')->deleteDirectory('hooky/profile');
        Storage::disk('r2')->deleteDirectory('hooky/qr');

        $company = Company::create([
            "uid" => "1d59e992-7865-41c5-ad7d-d271ccf4e7fc",
            "name"=> "Studio54",
            "email"=> "test@test.es",
            "password"=> "a",
            "timezone_uid" => TimeZone::find(2)->uid,
            "pricing_plan_uid" => \App\Models\PricingPlan::find(1)->uid
        ]);

        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $company->link);

        Storage::disk('r2')->put('hooky/qr/' . $company->uid . '.png', $response->body());

        $this->call(EventsMockSeeder::class);
        $this->call(UserMockSeeder::class);

        // for($i = 0; $i < 3000; $i++) {
        //     User::create([
        //         'name' => fake()->name(),
        //         'surnames' => fake()->name(),
        //         'email' => fake()->unique()->safeEmail(),
        //         'password' => Hash::make('a'),
        //         "gender_id" => $i % 2 == 0 ? Gender::MALE : Gender::FEMALE,
        //         "sexual_orientation_id" => SexualOrientation::HETEROSEXUAL,
        //         "role_id" => Role::USER,
        //         "city" => fake()->city(),
        //         "born_date" => fake()->date(),
        //         "ig" => fake()->name(),
        //         "tw" => fake()->name(),
        //         "description" => fake()->paragraph(),
        //     ]);

        // }
        

        
        User::all()->each(function ($user)  {
            $randomEvents = Event::find(1);
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
