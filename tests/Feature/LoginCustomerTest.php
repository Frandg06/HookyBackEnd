<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class LoginCustomerTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    protected $user;
    protected $company;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'a@a.es',
            'password' => bcrypt('123456'),
        ]);
        $this->company = Company::factory()->create();
    }
    /**
     * A basic feature test example.
     */
    public function test_login_return_a_successful_response(): void
    {
        Event::factory()->create([
            'company_uid' => $this->company->uid,
            'st_date' => now()->subDays(1),
            'end_date' => now()->addDays(1),
        ]);
        $res = $this->postJson('api/customers/auth/login', [
            'email' => $this->user->email,
            'password' => '123456',
            'company_uid' => Crypt::encrypt($this->company->uid),
        ]);
        $res->assertStatus(200);

        $res->assertJsonStructure([
            'success',
            'access_token',
        ]);
    }

    public function test_login_return_a_event_not_existing_response(): void
    {
        $res = $this->postJson('api/customers/auth/login', [
            'email' => $this->user->email,
            'password' => '123456',
            'company_uid' => Crypt::encrypt($this->company->uid),
        ]);
        $res->assertStatus(404);

        $res->assertJson([
            'error' => true,
            'custom_message' => __('i18n.event_not_active'),
        ]);
    }

    public function test_login_return_a_event_not_active_response(): void
    {
        Event::factory()->create([
            'company_uid' => $this->company->uid,
            'st_date' => now()->subDays(2),
            'end_date' => now()->subDays(1),
        ]);

        $res = $this->postJson('api/customers/auth/login', [
            'email' => $this->user->email,
            'password' => '123456',
            'company_uid' => Crypt::encrypt($this->company->uid),
        ]);

        $res->assertStatus(404);

        $res->assertJson([
            'error' => true,
            'custom_message' => __('i18n.event_not_active'),
        ]);
    }

    public function test_login_return_succes_with_next_event_response(): void
    {
        Event::factory()->create([
            'company_uid' => $this->company->uid,
            'st_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
        ]);

        $res = $this->postJson('api/customers/auth/login', [
            'email' => $this->user->email,
            'password' => '123456',
            'company_uid' => Crypt::encrypt($this->company->uid),
        ]);

        $res->assertStatus(200);

        $res->assertJsonStructure([
            'success',
            'access_token',
        ]);
    }

    public function test_login_without_email_response(): void
    {
        Event::factory()->create([
            'company_uid' => $this->company->uid,
            'st_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
        ]);

        $res = $this->postJson('api/customers/auth/login', [
            // 'email' => $this->user->email,
            'password' => '123456',
            'company_uid' => Crypt::encrypt($this->company->uid),
        ]);

        $res->assertStatus(422);
        $res->assertJsonStructure([
            'error',
            'message' => [
                'email',
            ],
        ]);
    }

    public function test_login_with_fake_email_response(): void
    {
        Event::factory()->create([
            'company_uid' => $this->company->uid,
            'st_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
        ]);

        $res = $this->postJson('api/customers/auth/login', [
            'email' => "fake@fake.es",
            'password' => '123456',
            'company_uid' => Crypt::encrypt($this->company->uid),
        ]);

        $res->assertStatus(401);
        $res->assertJson([
            'error' => true,
            'custom_message' => __('i18n.credentials_ko'),
        ]);
    }

    public function test_login_with_wrong_password(): void
    {
        Event::factory()->create([
            'company_uid' => $this->company->uid,
            'st_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
        ]);

        $res = $this->postJson('api/customers/auth/login', [
            'email' => $this->user->email,
            'password' => '123456123132',
            'company_uid' => Crypt::encrypt($this->company->uid),
        ]);

        $res->assertStatus(401);
        $res->assertJson([
            'error' => true,
            'custom_message' => __('i18n.credentials_ko'),
        ]);
    }

    public function test_login_with_wrong_company(): void
    {
        Event::factory()->create([
            'company_uid' => $this->company->uid,
            'st_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
        ]);

        $res = $this->postJson('api/customers/auth/login', [
            'email' => $this->user->email,
            'password' => '123456123132',
            'company_uid' => Crypt::encrypt(123123123),
        ]);

        $res->assertStatus(404);
        $res->assertJson([
            'error' => true,
            'custom_message' => __('i18n.company_not_exists'),
        ]);
    }
}
