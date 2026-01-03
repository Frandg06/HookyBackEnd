<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;

use function Pest\Laravel\postJson;

describe('register user', function () {
    it('user can register successfully without event', function () {
        // Arrange
        $payload = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Act
        $response = postJson(route('customer.register'), $payload);

        // Assert
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'message',
            'content' => [
                'access_token',
            ],
            'success',
        ]);

        assertDatabaseHasRecord('users', [
            'email' => 'john.doe@example.com',
        ]);
    });

    it('user can register successfully with event', function () {
        // Arrange
        $event = Event::factory()->create([
            'st_date' => now()->subHour(),
            'end_date' => now()->addHours(5),
        ]);

        $payload = [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'event_uid' => $event->uid,
        ];

        // Act
        $response = postJson(route('customer.register'), $payload);

        // Assert
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'message',
            'content' => [
                'access_token',
            ],
            'success',
        ]);
        assertDatabaseHasRecord('users', [
            'email' => 'jane.smith@example.com',
        ]);

        assertDatabaseHasRecord('user_events', [
            'event_uid' => $event->uid,
            'user_uid' => User::where('email', 'jane.smith@example.com')->first()->uid,
        ]);
    });

    it('registration fails with missing fields', function () {
        // Arrange
        $payload = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ];

        // Act
        $response = postJson(route('customer.register'), $payload);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    });

    it('registration fails with existing email', function () {
        // Arrange
        createUser(['email' => 'existing.email@example.com']);
        $payload = [
            'name' => 'Existing User',
            'email' => 'existing.email@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Act
        $response = postJson(route('customer.register'), $payload);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    });

    it('registration fails with short password', function () {
        // Arrange
        $payload = [
            'name' => 'Event User',
            'email' => 'event.user@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        // Act
        $response = postJson(route('customer.register'), $payload);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    });

    it('user can register but event attachment fails because event is inactive', function () {
        // Arrange
        $event = Event::factory()->create([
            'st_date' => now()->subDays(2),
            'end_date' => now()->subDay(),
        ]);
        $payload = [
            'name' => 'Inactive Event User',
            'email' => 'inactive.event.user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'event_uid' => $event->uid,
        ];

        // Act
        $response = postJson(route('customer.register'), $payload);

        // Assert
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'message',
            'content' => [
                'access_token',
            ],
            'success',
        ]);
        assertDatabaseHasRecord('users', [
            'email' => 'inactive.event.user@example.com',
        ]);
        assertDatabaseMissingRecord('user_events', [
            'event_uid' => $event->uid,
            'user_uid' => User::where('email', 'inactive.event.user@example.com')->first()->uid,
        ]);
    });

    it('registration works with invalid event_uid', function () {
        // Arrange
        $payload = [
            'name' => 'Inactive Event User',
            'email' => 'inactive.event.user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'event_uid' => 'non-existent-uid',
        ];

        // Act
        $response = postJson(route('customer.register'), $payload);

        // Assert
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'message',
            'content' => [
                'access_token',
            ],
            'success',
        ]);
        assertDatabaseHasRecord('users', [
            'email' => 'inactive.event.user@example.com',
        ]);
        assertDatabaseMissingRecord('user_events', [
            'user_uid' => User::where('email', 'inactive.event.user@example.com')->first()->uid,
        ]);
    });

    it('registration works with non existing event_uid', function () {
        // Arrange
        $payload = [
            'name' => 'Mismatch Password User',
            'email' => 'example.user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'event_uid' => Str::uuid(),
        ];

        // Act
        $response = postJson(route('customer.register'), $payload);

        // Assert
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'message',
            'content' => [
                'access_token',
            ],
            'success',
        ]);
        assertDatabaseHasRecord('users', [
            'email' => 'example.user@example.com',
        ]);
        assertDatabaseMissingRecord('user_events', [
            'event_uid' => $payload['event_uid'],
            'user_uid' => User::where('email', 'example.user@example.com')->first()->uid,
        ]);
    });
});
