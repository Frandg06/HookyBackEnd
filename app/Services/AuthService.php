<?php
namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\AuthUserReosurce;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AuthService {

    public function register($data) {
      DB::beginTransaction();
      try {

        $user = User::where('email', $data['email'])->first();
        
        if($user) throw new CustomException("El usuario ya existe");
        
        $company_uid = Crypt::decrypt($data['company_uid']);

        $company = Company::where('uid', $company_uid)->first();
        
        if(!$company) throw new CustomException("El organzador no existe");

        
        $timezone = $company->timezone->name;
        
        $event = $company->events()->activeEvent($timezone)->first();
        
        if(!$event) throw new CustomException("Actualmente no hay eventos activos");
        
        $user = User::create([
          ...$data,
          "like_credits" => $event->likes,
          "super_like_credits" => $event->super_likes
        ]);
        
        $user->events()->create([
          'event_uid' => $event->uid, 
          'logged_at' => now(), 
          'likes' => $event->likes, 
          'super_likes' => $event->super_likes
        ]);
        
        $now = Carbon::now($timezone);
        $end_date = Carbon::parse($event->end_date);
        $diff = $now->diffInMinutes($end_date);
        
        $token = $user->createToken('auth_token', ['*'], now()->addMinutes($diff))->plainTextToken;
        
        DB::commit();

        return (object)[
          'user' => $user,
          'access_token' => $token,
        ];

      } catch (Throwable $e) {
        DB::rollBack();
        ($e instanceof CustomException)
          ? throw new \Exception($e->getMessage())
          : throw new \Exception("Se ha producido un error al registrar el usuario");
      }
    }

    public function registerCompany($data) {
      try {
        $company = Company::where('email', $data['email'])->first();
  
        if($company) throw new CustomException("El usuario ya existe");
  
        $company = Company::create($data);
  
        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $company->link);
  
        Storage::disk('r2')->put('hooky/qr/' . $company->uid . '.png', $response->body());
  
        $token = $company->createToken('company_auth_token', ['*'], now()->addHours(1))->plainTextToken;
  
        return (object)[
            'user' => $company,
            'access_token' => $token,
        ];

      }catch (\Throwable $e) {
        ($e instanceof CustomException)
        ? throw new \Exception($e->getMessage())
        : throw new \Exception("Se ha producido un error al registrar la empresa");
      }
        
    }

    public function login($data, $company_uid) {

      DB::beginTransaction();
      try {
        if (!Auth::attempt($data)) {
          throw new CustomException('Las credenciales no son correctas');
        }

        $company_uid = Crypt::decrypt($company_uid);

        $company = Company::where('uid', $company_uid)->first();
          
        if(!$company) throw new CustomException("El organzador no existe");

        $timezone = $company->timezone->name;
          
        $event = $company->events()->activeEvent($timezone)->first();

        if(!$event) throw new CustomException("Actualmente no hay eventos activos");

        $user = User::where('email', $data['email'])->get()->firstOrFail();

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

        $now = Carbon::now($timezone);
        $end_date = Carbon::parse($event->end_date);
        $diff = $now->diffInMinutes($end_date);


        $token =  $user->createToken('auth_token', ['*'], now()->addMinutes($diff))->plainTextToken;

        DB::commit();

        return (object)[
            'user' => AuthUserReosurce::make($user),
            'access_token' => $token,
        ];
      }
      catch (Throwable $e) {
        DB::rollBack();
        ($e instanceof CustomException)
          ? throw new \Exception($e->getMessage())
          : throw new \Exception("Se ha producido un error al iniciar sesion");
      }
    }

    public function loginCompany($data) {

      try {
        $company = Company::where('email', $data['email'])->first();

        if (!$company || !Hash::check($data['password'], $company->password)) {
          throw new CustomException('Invalid credentials');
        }
  
        $token = $company->createToken('company_auth_token', ['*'], now()->addHours(1))->plainTextToken;
  
        return (object)[
            'company' => $company,
            'access_token' => $token,
        ];
      } catch (\Throwable $e) {
        ($e instanceof CustomException)
        ? throw new \Exception($e->getMessage())
        : throw new \Exception("Se ha producido un error al iniciar sesion");
      }
     
    }

    public function update(User $user, $data) {
      try {

        $user->update($data);

        if( isset($user['gender_id']) || isset( $user['sexual_orientation_id'] )) {
          $user->interactions()->delete();
        }
        
        return AuthUserReosurce::make($user);

      } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
      }

    }

    public function setCompany($request) {
      
      if(!$request->user()){
        throw new \Exception("The user not exist");
      }

      $request->user()->company_id = $request->company_id;
      $request->user()->save();

      return $request->user()->company_id;
    }

    public function changePassword($data, $user) {
      try {
        
        if(!Hash::check($data['old_password'], $user->password)) {
          throw new \Exception("La contraseña actual es incorrecta");
        }

        $user->password = bcrypt($data['password']);
        $user->save();

        return true;
      } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
      }
    }
}