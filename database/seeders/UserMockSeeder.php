<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\Role;
use App\Models\SexualOrientation;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserMockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            [
                'name' => 'Fran',
                'surnames' => 'Diez',
                'email' => 'a@a.es',
                'password' => 'a',
                'gender_id' => Gender::MALE,
                'sexual_orientation_id' => SexualOrientation::HETEROSEXUAL,
                'role_id' => Role::PREMIUM,
                'born_date' => '1990-01-01',
                'description' => 'Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.',
            ],
            [
                'name' => 'Celia',
                'surnames' => 'De la Puente',
                'email' => 'b@b.es',
                'password' => 'a',
                'gender_id' => Gender::FEMALE,
                'sexual_orientation_id' => SexualOrientation::HETEROSEXUAL,
                'role_id' => Role::USER,
                'born_date' => '1990-01-01',
                'description' => 'Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.',

            ],
            [
                'name' => 'Clara',
                'surnames' => 'Garcia',
                'email' => 'c@c.es',
                'password' => 'a',
                'gender_id' => Gender::FEMALE,
                'sexual_orientation_id' => SexualOrientation::HOMOSEXUAL,
                'role_id' => Role::USER,
                'born_date' => '1990-01-01',
                'description' => 'Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.',
            ],
            [
                'name' => 'Irene',
                'surnames' => 'MArtinez',
                'email' => 'd@d.es',
                'password' => 'a',
                'gender_id' => Gender::FEMALE,
                'sexual_orientation_id' => SexualOrientation::BISEXUAL,
                'role_id' => Role::USER,
                'born_date' => '1990-01-01',
                'description' => 'Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.',
            ],
            [
                'name' => 'Laura',
                'surnames' => 'Perez',
                'email' => 'e@e.es',
                'password' => 'a',
                'gender_id' => Gender::FEMALE,
                'sexual_orientation_id' => SexualOrientation::HOMOSEXUAL,
                'role_id' => Role::USER,
                'born_date' => '1990-01-01',
                'description' => 'Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.',

            ],
            [
                'name' => 'Daniel',
                'surnames' => 'Diaz',
                'email' => 'f@f.es',
                'password' => 'a',
                'gender_id' => Gender::MALE,
                'sexual_orientation_id' => SexualOrientation::BISEXUAL,
                'role_id' => Role::USER,
                'born_date' => '1990-01-01',
                'description' => 'Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.',
            ],
            [
                'name' => 'Tetor',
                'surnames' => 'Tetor',
                'email' => 'x@x.es',
                'password' => 'a',
                'gender_id' => Gender::MALE,
                'sexual_orientation_id' => SexualOrientation::HOMOSEXUAL,
                'role_id' => Role::USER,
                'born_date' => '1990-01-01',
                'description' => 'Sit excepteur mollit duis elit elit sit cupidatat proident adipisicing. Dolor reprehenderit labore tempor sit est dolor. Velit aliqua cupidatat exercitation mollit nulla Lorem nostrud. Cupidatat ut laborum laborum minim dolore deserunt ad in anim aliqua ex commodo eu. Ut sint proident cillum in tempor eu.',
            ],
        ];

        foreach ($arr as $user) {
            User::create($user);
        }
    }
}
