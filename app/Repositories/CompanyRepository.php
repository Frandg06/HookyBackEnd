<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Company;

final class CompanyRepository
{
    public function getCompanyByUuid(string $uuid): Company
    {
        return Company::find($uuid);
    }

    public function findCompanyByEmail(string $email): ?Company
    {
        return Company::where('email', $email)->first();
    }
}
