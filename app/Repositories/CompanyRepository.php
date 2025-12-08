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
}
