<?php

declare(strict_types=1);

namespace App\Http\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\TimeZone;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CompanyPasswordResetToken;

final class AuthCompanyService extends Service
{
    public function register(array $data): string
    {
        DB::beginTransaction();
        try {
            $company = Company::where('email', $data['email'])->first();

            if ($company) {
                throw new ApiException('user_exists', 409);
            }

            if (! isset($data['timezone_uid']) || empty($data['timezone_uid'])) {
                $data['timezone_uid'] = TimeZone::where('name', 'Europe/Berlin')->first()->uid;
            }

            $company = Company::create($data);

            Auth::setTTL(24 * 60);

            $token = Auth::guard('company')->attempt(['email' => $data['email'], 'password' => $data['password']]);

            DB::commit();

            return $token;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(array $data): string
    {
        $company = Company::where('email', $data['email'])->first();

        if (! $company || ! Hash::check($data['password'], $company->password)) {
            throw new ApiException('credentials_ko', 401);
        }

        Auth::setTTL(24 * 60);
        $token = Auth::guard('company')->attempt($data);

        return $token;
    }

    public function updatePassword(array $data): bool
    {
        DB::beginTransaction();
        try {
            $company = $this->company();

            if (! $company) {
                throw new ApiException('user_not_found', 404);
            }

            if (! Hash::check($data['old_password'], $company->password)) {
                throw new ApiException('actual_password_ko', 400);
            }

            $company->update([
                'password' => bcrypt($data['new_password']),
            ]);
            DB::commit();

            return true;
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

            $company = Company::where('email', $email)->first();

            if (! $company) {
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
                'expires_at' => now()->addMinutes(15),
            ]);

            $url = config('app.admin_url').'/password/new?token='.$password_token->token;

            $email_service = new EmailService();
            $email_service->sendPasswordResetEmail($company, $url);
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
            $token_model = CompanyPasswordResetToken::where('token', $data['token'])->first();

            if (! $token_model) {
                throw new ApiException('token_not_found', 404);
            }

            if (now()->greaterThan(Carbon::parse($token_model->expires_at))) {
                throw new ApiException('token_expired', 404);
            }

            $company = $token_model->company;

            $company->update([
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
}
