<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Gender;
use App\Models\Interest;
use App\Models\Role;
use App\Models\SexualOrientation;
use App\Models\TimeZone;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Gender::create([
            'name' => 'Female',
        ]);

        Gender::create([
            'name' => 'Male',
        ]);

        Role::create([
            'name' => 'Admin',
        ]);

        Role::create([
            'name' => 'User',
        ]);

        Role::create([
            'name' => 'Vip',
        ]);

        SexualOrientation::create([
            'name' => 'Bisexual',
        ]);

        SexualOrientation::create([
            'name' => 'Heterosexual',
        ]);

        SexualOrientation::create([
            'name' => 'Homosexual',
        ]);
        
        $this->call(InterestSeeder::class);
        $this->call(InteractionSeeder::class);
        $this->call(TimeZonesSeeder::class);

        $company = Company::create([
            "name"=> "Studio54",
            "email"=> "studio54@email.es",
            "password"=> "a",
            "timezone_uid" => TimeZone::find(2)->uid
        ]);


        $new = User::create([
            'name' => 'Admin',
            'surnames' => "Fran",
            'email' => 'a@a.es',
            'password' => bcrypt('a'),
            'like_credits' => 20,
            'super_like_credits' => 3,
            "gender_id" => 2,
            "sexual_orientation_id" => 2,
            "role_id" => 2,
            "city" => "Leon",
            "born_date" => "1990-01-01",
            "ig" => "frandiez",
            "tw" => "frandiez",
            "description" => "Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.",
        ]);

        $new->interestBelongsToMany()->attach([1,2,3]);

        
        User::factory(UserFactory::new())->count(10)->create()->each(function ($user) {
            $interests = Interest::inRandomOrder()->limit(3)->pluck('id');
            $user->interestBelongsToMany()->attach($interests);

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

        });
    }
}
