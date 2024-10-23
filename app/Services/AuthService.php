<?php
namespace App\Services;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthService {

    public function register($data) {

      $companyDefaultData = [
        'like_credits' => 20,
        'super_like_credits' => 3,
      ];

      $paseData = array_merge($companyDefaultData, $data);

      $user = User::create($paseData);

      $token = $user->createToken('auth_token', ['*'], now()->addHours(1))->plainTextToken;

      return [
          'user' => $user,
          'access_token' => $token,
          'token_type' => 'Bearer',
      ];
        
    }

    public function login($data) {

      if (!Auth::attempt($data)) {
        throw new \Exception('Invalid credentials');
      }

      $user = User::where('email', $data['email'])->get()->firstOrFail();

      $token =  $user->createToken('auth_token', ['*'], now()->addHours(1))->plainTextToken;
      return [
          'user' => $user,
          'access_token' => $token,
          'token_type' => 'Bearer',
      ];
    }

    public function completeInfo(User $user, $data) {
      try {
        $user->completeInfo($data);

        $user->isDataComplete();
        
        return $user;

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
}