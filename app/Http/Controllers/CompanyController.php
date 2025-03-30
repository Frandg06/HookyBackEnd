<?php

namespace App\Http\Controllers;

use App\Http\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function update(Request $request)
    {
        $data = $request->only(['name', 'email', 'phone', 'address', 'city', 'country', 'timezone_string']);
        $response = $this->companyService->update($data);

        return response()->json(['success' => true,  'resp' => $response]);
    }
}
