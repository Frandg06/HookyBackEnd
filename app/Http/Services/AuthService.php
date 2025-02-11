<?php
namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\AuthUserReosurce;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthService {

    public function register($data) {
      DB::beginTransaction();
      try {
        $user = User::where('email', $data['email'])->first();

        if($user) throw new ApiException("user_exists", 409);
        
        $company_uid = Crypt::decrypt($data['company_uid']);

        $company = Company::where('uid', $company_uid)->first();
        
        if(!$company) throw new ApiException("company_not_exists", 404);

        
        $timezone = $company->timezone->name;
        
        $event = $company->events()->activeEvent($timezone)->first();
        
        if(!$event) throw new ApiException("event_not_active", 404);
        
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

      } catch (ApiException $e) { 
        DB::rollBack();
        throw new ApiException($e->getMessage(), $e->getCode());
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new ApiException("register_user_ko", 500);
      }
    }

    public function login($data, $company_uid) {

      DB::beginTransaction();

      try {

        $company_uid = Crypt::decrypt($company_uid);

        $company = Company::where('uid', $company_uid)->first();
          
        if(!$company) throw new ApiException(__("i18n.company_not_exists"));

        $timezone = $company->timezone->name;
        
        $event = $company->events()->activeEvent($timezone)->first();

        if(!$event) throw new ApiException(__("i18n.event_not_active"));

        $now = Carbon::now($timezone);
        $end_date = Carbon::parse($event->end_date);
        $diff = $now->diffInMinutes($end_date);
        
        $token = Auth::setTTL($diff)->attempt($data);

        if (!$token)  throw new ApiException(__("i18n.credentials_ko"));

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
      catch (ApiException $e) {
        DB::rollBack();
        throw new \Exception($e->getMessage());
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__("i18n.login_ko"));
      }
    }
}