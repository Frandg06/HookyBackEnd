<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\Interest;
use App\Models\Role;
use App\Models\SexualOrientation;
use App\Models\User;
use App\Models\UserInterest;
use Database\Factories\UserFactory;
use Database\Factories\UserInterestFactory;
use Illuminate\Database\Seeder;

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


        $new = User::create([
            'name' => 'Admin',
            'surnames' => "Fran",
            'email' => 'a@a.es',
            'password' => bcrypt('a'),
            'like_credits' => 20,
            'super_like_credits' => 3,
            "gender_id" =>2,
            "sexual_orientation_id" => 2,
            "role_id" => 2,
            "city" => "Leon",
            "born_date" => "1990-01-01",
            "ig" => "frandiez",
            "tw" => "frandiez",
            "description" => "Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.",
        ]);

        $new->interestBelongsToMany()->attach([1,2,3]);

        
        User::factory(UserFactory::new())->count(100)->create()->each(function ($user) {
            $interests = Interest::inRandomOrder()->limit(3)->pluck('id');
            $user->interestBelongsToMany()->attach($interests);
        });
    }
}
