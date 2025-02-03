<?php
namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\AuthUserReosurce;
use App\Models\Company;
use App\Models\TimeZone;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthService {

    public function register($data) {
      DB::beginTransaction();
      try {

        $user = User::where('email', $data['email'])->first();
        
        if($user) throw new CustomException(__("i18n.user_exists"));
        
        $company_uid = Crypt::decrypt($data['company_uid']);

        $company = Company::where('uid', $company_uid)->first();
        
        if(!$company) throw new CustomException(__("i18n.company_not_exists"));

        
        $timezone = $company->timezone->name;
        
        $event = $company->events()->activeEvent($timezone)->first();
        
        if(!$event) throw new CustomException(__("i18n.event_not_active"));
        
        $user = User::create($data);
        
        $user->events()->create([
          'event_uid' => $event->uid, 
          'logged_at' => now(), 
          'likes' => $event->likes, 
          'super_likes' => $event->super_likes
        ]);
        
        $now = Carbon::now($timezone);
        $end_date = Carbon::parse($event->end_date);
        $diff = $now->diffInMinutes($end_date);
        
        $token = Auth::setTTL($diff)->attempt(['email' => $data['email'], 'password' => $data['password']]);
        
        DB::commit();

        return (object)[
          'user' => $user,
          'access_token' => $token,
        ];

      } catch (CustomException $e) { 
        DB::rollBack();
        throw new \Exception($e->getMessage());
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__("i18n.register_user_ko"));
      }
    }

    public function registerCompany($data) {
      DB::beginTransaction();
      try {

        $company = Company::where('email', $data['email'])->first();
        
        if($company) throw new CustomException(__("i18n.user_exists"));
        
        if(!isset($data['timezone_uid']) || empty($data['timezone_uid'])){
          $data['timezone_uid'] = TimeZone::where('name', 'Europe/Berlin')->first()->uid;
        } 

        $company = Company::create($data);
  
        $response = Http::get(env('QR_API_URL') . $company->link);
  
        Storage::disk('r2')->put('hooky/qr/' . $company->uid . '.png', $response->body());
  
        Auth::setTTL(24*60);
        $token = Auth::guard('company')->attempt(['email' => $data['email'], 'password' => $data['password']]);
  
        DB::commit();

        return (object)[
            'user' => $company,
            'access_token' => $token,
        ];

      }catch (CustomException $e) {
        DB::rollBack();
        throw new \Exception($e->getMessage());
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__("i18n.register_company_ko"));
      }
        
    }

    public function login($data, $company_uid) {

      DB::beginTransaction();

      try {


        $company_uid = Crypt::decrypt($company_uid);

        $company = Company::where('uid', $company_uid)->first();
          
        if(!$company) throw new CustomException(__("i18n.company_not_exists"));

        $timezone = $company->timezone->name;
        
        $event = $company->events()->activeEvent($timezone)->first();

        if(!$event) throw new CustomException(__("i18n.event_not_active"));

        $now = Carbon::now($timezone);
        $end_date = Carbon::parse($event->end_date);
        $diff = $now->diffInMinutes($end_date);
        
        $token = Auth::setTTL($diff)->attempt($data);

        if (!$token)  throw new CustomException(__("i18n.credentials_ko"));

        $user = request()->user();

        $exist = $user->events()->where('event_uid', $event->uid)->exists();

        if($exist){
          $user->events()->where('event_uid', $event->uid)->update(['logged_at' => now()]);
        }else{
          $user->events()->create([
            'event_uid' => $event->uid, 
            'logged_at' => now(), 
            'likes' => $event->likes, 
            'super_likes' => $event->super_likes
          ]);
        }

        

        DB::commit();

        return (object)[
            'user' => AuthUserReosurce::make($user),
            'access_token' => $token,
        ];
      }
      catch (CustomException $e) {
        DB::rollBack();
        throw new \Exception($e->getMessage());
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__("i18n.login_ko"));
      }
    }

    public function loginCompany($data) {

      try {
        $company = Company::where('email', $data['email'])->first();

        if (!$company || !Hash::check($data['password'], $company->password)) {
          throw new CustomException(__("i18n.credentials_ko"));
        }

        Auth::setTTL(24*60);
        $token = Auth::guard('company')->attempt($data);

        return (object)[
            'company' => $company,
            'access_token' => $token,
        ];
      }catch (CustomException $e) {
          DB::rollBack();
          throw new \Exception($e->getMessage());
      } catch (\Exception $e) {
          DB::rollBack();
          Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
          throw new \Exception(__("i18n.login_ko"));
      }
     
    }
}