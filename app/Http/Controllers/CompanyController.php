<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use Illuminate\Http\Request;


class CompanyController extends Controller
{

    protected $companyService;
    public function __construct(CompanyService $companyService) {
        $this->companyService = $companyService;
        
    }
    public function getLink(Request $request) {
        $company = $request->user();
        return response()->json([
            'status' => 'success',
            'resp' => $company->link,
        ]);
    }

    public function update(Request $request) {
        try {
            $data = $request->only(['name', 'email', 'phone', 'address', 'city', 'country', 'timezone_string']);
            $company = $request->user();
            $response = $this->companyService->update($company, $data);
            return response()->json(['success' => true,  'resp' => $response]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
