<?php

declare(strict_types=1);

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Services\CompanyService;
use Illuminate\Http\Request;

final class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function update(Request $request)
    {
        $data = $request->only(['name', 'email', 'phone', 'address', 'country', 'timezone_string', 'cif', 'website']);
        $response = $this->companyService->update($data);

        return response()->json(['success' => true,  'resp' => $response]);
    }
}
