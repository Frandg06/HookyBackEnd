<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Models\User;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

function createUser(array $attributes = [])
{
    return User::factory()->create($attributes);
}

function actingAsUser(?User $user = null): Tests\TestCase
{
    $user ??= createUser();

    return test()->actingAs($user, 'api');
}

function assertDatabaseHasRecord(string $table, array $data): void
{
    test()->assertDatabaseHas($table, $data);
}

function assertDatabaseMissingRecord(string $table, array $data): void
{
    test()->assertDatabaseMissing($table, $data);
}
