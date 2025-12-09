<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Actions\Consumer\AttachUserToCompanyEvent;
use App\Exceptions\ApiException;
use App\Http\Resources\UserResource;
use App\Models\Event;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Models\UserEvent;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

final class AuthService extends Service
{
    public function __construct(
        private readonly AttachUserToCompanyEvent $attachUserToCompanyEvent,
        private readonly UserRepository $userRepository,
    ) {}

    public function register(array $data): string
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                throw new ApiException('user_exists', 409);
            }

            $user = User::create($data);

            if (filled($data['company_uid'])) {
                [$user, $event] = $this->attachUserToCompanyEvent->execute($user, $data['company_uid']);
            }

            $diff = $this->getDiff($event);

            $token = Auth::setTTL($diff)->attempt(['email' => $data['email'], 'password' => $data['password']]);

            DB::commit();

            return $token;
        } catch (Exception $e) {
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

            if (filled($data['company_uid'])) {
                [$user, $event] = $this->attachUserToCompanyEvent->execute($user, $data['company_uid']);
            }

            $diff = $this->getDiff($event);
            $token = Auth::setTTL($diff)->attempt(['email' => $data['email'], 'password' => $data['password']]);

            if (! $token) {
                throw new ApiException('credentials_ko', 401);
            }

            DB::commit();

            return $token;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function socialLogin(array $data): string
    {
        DB::beginTransaction();
        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver($data['provider']);
            $socialUser = $driver->userFromToken($data['access_token']);

            $user = $this->userRepository->getUserByEmail($socialUser->getEmail());

            if (! $user) {
                $user = $this->userRepository->createUserFromSocialLogin($socialUser, $data['provider']);
            }

            if (isset($data['company_uid']) && filled($data['company_uid'])) {
                [$user, $event] = $this->attachUserToCompanyEvent->execute($user, $data['company_uid']);
            }

            $diff = $this->getDiff($event ?? null);

            $token = Auth::setTTL($diff)->login($user);

            DB::commit();

            return $token;

        } catch (Exception $e) {
            DB::rollBack();
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
        } catch (Exception $e) {
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

            $token = Str::random(64);

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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getDiff(?Event $event): float
    {
        $date = $event->end_date ?? null;
        $tz = $event->timezone ?? 'Europe/Berlin';

        if (! $date) {
            $date = now($tz)->addHours(12)->format('Y-m-d H:i');
        }

        $end_date = Carbon::parse($date);

        return now($tz)->diffInMinutes($end_date);
    }
}
