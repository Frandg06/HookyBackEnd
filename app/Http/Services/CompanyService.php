<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\AuthCompanyResource;
use App\Models\TimeZone;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CompanyService extends Service
{
    public function update(array $data)
    {
        DB::beginTransaction();
        try {
            $company = $this->company();

            $timezone_uid = TimeZone::where('name', $data['timezone_string'])->first()->uid;

            if (! $timezone_uid) {
                throw new ApiException('timezone_not_found', 400);
            }

            $company->update([...$data, 'timezone_uid' => $timezone_uid]);

            DB::commit();

            return AuthCompanyResource::make($company);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->log($e, __CLASS__, __FUNCTION__);
            throw $e;
        }
    }
}
