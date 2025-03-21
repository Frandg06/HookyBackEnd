<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\AuthCompanyResource;
use App\Models\TimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyService
{
    public function update(array $data)
    {
        DB::beginTransaction();
        try {
            $company = request()->user();

            $timezone_uid = TimeZone::where('name', $data['timezone_string'])->first()->uid;

            if (!$timezone_uid) {
                throw new ApiException('timezone_not_found', 400);
            }

            $company->update([...$data, 'timezone_uid' => $timezone_uid]);

            DB::commit();

            return AuthCompanyResource::make($company);
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('update_company_ko', 500);
        }
    }
}
