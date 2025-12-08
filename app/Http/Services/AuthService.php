<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Event;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Models\UserEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends Service
{
    public function register(array $data): string
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                throw new ApiException('user_exists', 409);
            }

            $user = User::create($data);

            if (isset($data['company_uid']) && ! empty($data['company_uid'])) {

                $user->update([
                    'company_uid' => $data['company_uid'],
                ]);

                $company = Company::find($data['company_uid']);

                $timezone = $company->timezone->name;

                $actual_event = $company->active_event;

                if (! $actual_event) {
                    $next_event = $company->next_event;
                }

                $event = $actual_event ?? $next_event;

                if ($event) {
                    $count = $event->users()->count();

                    if ($count >= $company->limit_users) {
                        throw new ApiException('limit_users_reached', 409);
                    }

                    $user->events()->attach($event->uid, [
                        'logged_at' => now(),
                        'likes' => $event->likes,
                        'super_likes' => $event->super_likes,
                    ]);
                }
            }

            $end_date = $event->end_date ?? null;
            $timezone = $event->timezone ?? null;

            $diff = $this->getDiff($end_date, $timezone);

            $token = Auth::setTTL(+$diff)->attempt(['email' => $data['email'], 'password' => $data['password']]);

            DB::commit();

            return $token;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(array $data): string
    {
        DB::beginTransaction();
        try {

            $user = User::where('email', $data['email'])->first();

            if (! $user) {
                throw new ApiException('credentials_ko', 401);
            }

            if (! Hash::check($data['password'], $user->password)) {
                throw new ApiException('credentials_ko', 401);
            }

            if (isset($data['company_uid']) && ! empty($data['company_uid'])) {

                $user->update([
                    'company_uid' => $data['company_uid'],
                ]);

                $company = Company::find($data['company_uid']);

                $timezone = $company->timezone->name;

                $actual_event = $company->active_event;

                if (! $actual_event) {
                    $next_event = $company->next_event;
                }

                $event = $actual_event ?? $next_event;

                if ($event) {
                    $exist = $user->events()->wherePivot('event_uid', $event->uid)->exists();
                    if (! $exist && $event->users->count() >= $company->limit_users) {
                        throw new ApiException('limit_users_reached', 409);
                    }
                    UserEvent::updateOrCreate(
                        [
                            'user_uid' => $user->uid,
                            'event_uid' => $event->uid,
                        ],
                        [
                            'logged_at' => now(),
                            'likes' => $event->likes,
                            'super_likes' => $event->super_likes,
                        ]
                    );
                }
            }

            $end_date = $event->end_date ?? null;
            $timezone = $event->timezone ?? null;

            $diff = $this->getDiff($end_date, $timezone);
            $token = Auth::setTTL($diff)->attempt(['email' => $data['email'], 'password' => $data['password']]);

            if (! $token) {
                throw new ApiException('credentials_ko', 401);
            }

            DB::commit();

            return $token;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function socialLogin(array $data): string
    {
        DB::beginTransaction();
        try {https://hooky-backend-5gvdds-4efb60-167-86-79-89.traefik.me/health
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver($data['provider']);
            $socialUser = $driver->userFromToken($data['access_token']);

            debug(['socialUser' => $socialUser]);
            
            $user = User::where('email', $socialUser->getEmail())->first();

            debug(['user_found' => $user]);

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider_name' => $data['provider'],
                    'provider_id' => $socialUser->getId(),
                    'password' => bcrypt(uniqid()),
                ]);
            }

            $token = JWTAuth::fromUser($user);            

            debug(['token' => $token]);
            
            DB::commit();

            return $token;

        } catch (\Exception $e) {
            DB::rollBack();
            debug(['error_social_login' => $e->getMessage()]);
            throw $e;
        }
    }

    public function loginIntoEvent(string $event_uid): UserResource
    {
        DB::beginTransaction();
        try {
            $user = user();

            $event = Event::find($event_uid);

            if (! $event) {
                throw new ApiException('event_not_found', 404);
            }

            if (! $event->is_active) {
                throw new ApiException('event_not_started', 409);
            }

            UserEvent::updateOrCreate(
                [
                    'user_uid' => $user->uid,
                    'event_uid' => $event->uid,
                ],
                [
                    'logged_at' => now(),
                    'likes' => $event->likes,
                    'super_likes' => $event->super_likes,
                ]
            );

            DB::commit();

            return $user->toResource();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function passwordReset(string $email): bool
    {
        DB::beginTransaction();
        try {
            if (! $email) {
                throw new ApiException('email_required', 400);
            }

            $user = User::where('email', $email)->first();

            if (! $user) {
                throw new ApiException('user_not_found', 404);
            }

            $token = uniqid(rand(), true);

            $already_used = PasswordResetToken::where('email', $user->email)->get();

            foreach ($already_used as $token) {
                $token->delete();
            }

            $password_token = PasswordResetToken::create([
                'email' => $user->email,
                'token' => base64_encode($token),
                'expires_at' => now()->addMinutes(15),
            ]);

            $url = config('app.front_url').'/auth/password/new?token='.$password_token->token;

            $template = view('emails.recovery_password_app', [
                'link' => $url,
                'name' => $user->name,
            ])->render();

            $emailService = new EmailService;
            $emailService->sendEmail($user, __('i18n.password_reset_subject'), $template);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function setNewPassword(array $data): bool
    {
        DB::beginTransaction();
        try {
            $token_model = PasswordResetToken::where('token', $data['token'])->first();

            if (! $token_model) {
                throw new ApiException('token_not_found', 404);
            }

            if (now()->greaterThan(Carbon::parse($token_model->expires_at))) {
                throw new ApiException('token_expired', 404);
            }

            $user = $token_model->user;

            $user->update([
                'password' => bcrypt($data['password']),
            ]);

            $token_model->delete();
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getDiff($date, $tz = 'Europe/Berlin')
    {
        if (! $date) {
            $date = now($tz)->addHours(12)->format('Y-m-d H:i');
        }

        $end_date = Carbon::parse($date);

        return now($tz)->diffInMinutes($end_date);
    }
}
