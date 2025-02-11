<?php
namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\Company;
use App\Models\TimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthCompanyService {

    public function register($data) {
      DB::beginTransaction();
      try {

        $company = Company::where('email', $data['email'])->first();
        
        if($company) throw new ApiException('user_exists', 409);
        
        if(!isset($data['timezone_uid']) || empty($data['timezone_uid'])){
          $data['timezone_uid'] = TimeZone::where('name', 'Europe/Berlin')->first()->uid;
        } 

        $company = Company::create($data);
  
        $response = Http::get(env('QR_API_URL') . $company->link);
  
        Storage::disk('r2')->put('hooky/qr/' . $company->uid . '.png', $response->body());
  
        Auth::setTTL(24*60);
        
        $token = Auth::guard('company')->attempt(['email' => $data['email'], 'password' => $data['password']]);
  
        DB::commit();

        return $token;

      }catch (ApiException $e) {
        DB::rollBack();
        throw new ApiException($e->getMessage(), $e->getCode());
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new ApiException("register_company_ko", 500);
      }
        
    }

    public function login($data) {
      try {

        $company = Company::where('email', $data['email'])->first();

        if (!$company || !Hash::check($data['password'], $company->password)) {
          throw new ApiException('credentials_ko', 401);
        }

        Auth::setTTL(24*60);
        
        $token = Auth::guard('company')->attempt($data);

        return $token;
      }catch (ApiException $e) {
        throw new ApiException($e->getMessage(), $e->getCode());
      } catch (\Exception $e) {
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new ApiException("login_ko", 500);
      }
     
    }
}