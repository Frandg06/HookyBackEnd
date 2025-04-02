<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\Company;
use App\Models\CompanyPasswordResetToken;
use App\Models\TimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthCompanyService extends Service
{
    public function register(array $data): string
    {
        DB::beginTransaction();
        try {
            $company = Company::where('email', $data['email'])->first();

            if ($company) {
                throw new ApiException('user_exists', 409);
            }

            if (!isset($data['timezone_uid']) || empty($data['timezone_uid'])) {
                $data['timezone_uid'] = TimeZone::where('name', 'Europe/Berlin')->first()->uid;
            }

            $company = Company::create($data);

            $response = Http::get(env('QR_API_URL') . $company->link);

            Storage::disk('r2')->put('hooky/qr/' . $company->uid . '.png', $response->body());

            Auth::setTTL(24 * 60);

            $token = Auth::guard('company')->attempt(['email' => $data['email'], 'password' => $data['password']]);

            DB::commit();

            return $token;
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('register_company_ko', 500);
        }
    }

    public function login(array $data): string
    {
        try {
            $company = Company::where('email', $data['email'])->first();

            if (!$company || !Hash::check($data['password'], $company->password)) {
                throw new ApiException('credentials_ko', 401);
            }

            Auth::setTTL(24 * 60);

            $token = Auth::guard('company')->attempt($data);

            return $token;
        } catch (ApiException $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('login_ko', 500);
        }
    }

    public function updatePassword(array $data)
    {
        DB::beginTransaction();
        try {
            $company = $this->company();

            if (!$company) {
                return $this->responseError('user_not_found', 404);
            }
            if (!Hash::check($data['old_password'], $company->password)) {
                return $this->responseError('passwords_dont_match', 404);
            }
            $company->update([
                'password' => bcrypt($data['new_password'])
            ]);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('unexpected_error', 500);
        }
    }

    public function passwordReset(string $email): bool
    {
        DB::beginTransaction();
        try {
            if (!$email) {
                throw new ApiException('email_required', 400);
            }

            $company = Company::where('email', $email)->first();

            if (!$company) {
                throw new ApiException('user_not_found', 404);
            }

            $token = uniqid(rand(), true);

            $already_used = CompanyPasswordResetToken::where('email', $company->email)->get();

            foreach ($already_used as $token) {
                $token->delete();
            }

            $password_token = CompanyPasswordResetToken::create([
                'email' => $company->email,
                'token' => base64_encode($token),
                'expires_at' => now()->addMinutes(15)
            ]);

            $url = config('app.admin_url') . '/password/new?token=' . $password_token->token;


            $template = view('emails.recovery_password_app', [
                'link' => $url,
                'name' => $company->name,
            ])->render();

            $emailService = new EmailService();
            $emailService->sendEmail($company, __('i18n.password_reset_subject'), $template);

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
            $token_model = CompanyPasswordResetToken::where('token', $data['token'])->first();

            if (!$token_model) {
                throw new ApiException('token_not_found', 404);
            }

            if (now()->greaterThan(Carbon::parse($token_model->expires_at))) {
                throw new ApiException('token_expired', 404);
            }

            $company = $token_model->company;

            $company->update([
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
}
