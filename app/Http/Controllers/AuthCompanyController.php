<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Resources\AuthCompanyResource;
use App\Http\Services\AuthCompanyService;
use App\Http\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCompanyController extends Controller
{
    protected $authService, $emailService;

    public function __construct(AuthCompanyService $authService, EmailService $emailService) { 
        $this->authService = $authService;
        $this->emailService = $emailService;
    }

    public function register(RegisterCompanyRequest $request) 
    {
        try {
            $validated = $request->only('name', 'email', 'phone', 'address', 'city', 'country', 'password');
            
            $response = $this->authService->register($validated);

            return response()->json(["success" => true, "access_token" =>  $response], 200);
        }  catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function login(Request $request) 
    {
        try {
            $data = $request->only('email', 'password');
            $response = $this->authService->login($data);

            return response()->json(["success" => true, "access_token" =>  $response], 200);
        }  catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function me() {
        try {
            $company = request()->user();

            $company = AuthCompanyResource::make($company);

            return response()->json(["resp" => $company, "success" => true], 200); 

        }catch (\Exception $e){
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 401);
        }
    }

    public function logout() 
    {
        Auth::invalidate(true);
        Auth::logout();
        
        return response()->json(["success" => true, 'message' => __('i18n.logged_out')], 200);
    }
}
