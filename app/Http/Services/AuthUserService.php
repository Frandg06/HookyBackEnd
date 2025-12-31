<?php

declare(strict_types=1);

namespace App\Http\Services;

use Exception;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Customer\UserResource;

final class AuthUserService extends Service
{
    public function updatePassword(array $data)
    {
        DB::beginTransaction();
        try {
            if ($this->user()->auto_password === false && ! Hash::check($data['old_password'], $this->user()->password)) {
                throw new ApiException('actual_password_ko', 400);
            }

            $this->user()->password = bcrypt($data['password']);
            $this->user()->auto_password = false;
            $this->user()->save();

            DB::commit();

            return UserResource::make($this->user()->loadRelations());
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
