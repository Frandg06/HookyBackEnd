<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\Company;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Models\UserEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

            $company_uid = Crypt::decrypt($data['company_uid']);

            $company = Company::where('uid', $company_uid)->first();

            if (!$company) {
                throw new ApiException('company_not_exists', 404);
            }

            $timezone = $company->timezone->name;

            $actual_event = $company->active_event;

            if (!$actual_event) {
                $next_event = $company->next_event;
            }

            $event = $actual_event ?? $next_event;

            if (!$event) {
                throw new ApiException('event_not_active', 404);
            }

            $user = User::create($data);

            $count = $event->users()->count();

            if ($count >= $company->limit_users) {
                throw new ApiException('limit_users_reached', 409);
            }

            $user->events()->create([
                'event_uid' => $event->uid,
                'logged_at' => now(),
                'likes' => $event->likes,
                'super_likes' => $event->super_likes
            ]);

            $diff = $this->getDiff($event->end_date, $timezone);

            $token = Auth::setTTL($diff)->attempt(['email' => $data['email'], 'password' => $data['password']]);

            DB::commit();

            return $token;
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('register_user_ko', 500);
        }
    }

    public function login(array $data, string $company_uid): string
    {
        DB::beginTransaction();
        try {
            $company_uid = Crypt::decrypt($company_uid);

            $company = Company::find($company_uid);

            if (!$company) {
                throw new ApiException('company_not_exists', 404);
            }

            $timezone = $company->timezone->name;

            $actual_event = $company->active_event;

            if (!$actual_event) {
                $next_event = $company->next_event;
            }

            $event = $actual_event ?? $next_event;

            if (!$event) {
                throw new ApiException('event_not_active', 404);
            }


            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                throw new ApiException('user_not_found', 404);
            }

            if (!Hash::check($data['password'], $user->password)) {
                throw new ApiException('credentials_ko', 401);
            }

            $exist = $user->events()->where('event_uid', $event->uid)->exists();

            if ($exist) {
                $user->events()->where('event_uid', $event->uid)->update(['logged_at' => now()]);
            } else {
                $count = $event->users->count();

                if ($count >= $company->limit_users) {
                    throw new ApiException('limit_users_reached', 409);
                }

                UserEvent::create([
                    'user_uid' => $user->uid,
                    'event_uid' => $event->uid,
                    'logged_at' => now(),
                    'likes' => $event->likes,
                    'super_likes' => $event->super_likes
                ]);
            }

            $diff = $this->getDiff($event->end_date, $timezone);
            $token = Auth::setTTL($diff)->attempt($data);

            if (!$token) {
                throw new ApiException('credentials_ko', 401);
            }

            DB::commit();

            return $token;
        } catch (ApiException $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('login_ko', 500);
        }
    }

    public function passwordReset(string $email): bool
    {
        DB::beginTransaction();
        try {
            if (!$email) {
                throw new ApiException('email_required', 400);
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
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
                'expires_at' => now()->addMinutes(15)
            ]);

            $url = config('app.front_url') . '/auth/password/new?token=' . $password_token->token;


            $template = view('emails.recovery_password_app', [
                'link' => $url,
                'name' => $user->name,
            ])->render();

            $emailService = new EmailService();
            $emailService->sendEmail($user, __('i18n.password_reset_subject'), $template);

            DB::commit();

            return true;
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('unexpected_error', 500);
        }
    }

    public function setNewPassword(array $data): bool
    {
        DB::beginTransaction();
        try {
            $token_model = PasswordResetToken::where('token', $data['token'])->first();

            if (!$token_model) {
                throw new ApiException('token_not_found', 404);
            }

            if (now()->greaterThan(Carbon::parse($token_model->expires_at))) {
                throw new ApiException('token_expired', 404);
            }

            $user = $token_model->user;

            $user->update([
                'password' => bcrypt($data['password'])
            ]);

            $token_model->delete();
            DB::commit();
            return true;
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('unexpected_error', 500);
        }
    }

    private function getDiff($end_date, $timezone)
    {
        $now = Carbon::now($timezone);
        $end_date = Carbon::parse($end_date);
        return $now->diffInMinutes($end_date);
    }
}
