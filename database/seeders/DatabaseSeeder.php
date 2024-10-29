<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\Role;
use App\Models\SexualOrientation;
use App\Models\User;
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

        User::create([
            'name' => 'Admin',
            'surnames' => "Fran",
            'email' => 'a@a.es',
            'password' => bcrypt('a'),
            'like_credits' => 20,
            'super_like_credits' => 3,
        ]);

        $this->call(InterestSeeder::class);
    }
}
