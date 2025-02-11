<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Services\CompanyService;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    protected $companyService;
    
    public function __construct(CompanyService $companyService) {
        $this->companyService = $companyService;
    }

    public function update(Request $request) {
        try {
            $data = $request->only(['name', 'email', 'phone', 'address', 'city', 'country', 'timezone_string', 'average_ticket_price']);
            $response = $this->companyService->update($data);
            return response()->json(['success' => true,  'resp' => $response]);
        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }
}
