<?php
namespace App\Services;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService {

    public function register($data) {

      $user = User::create($data);

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

      $user = User::where('email', $data['email'])->firstOrFail();

      $token = $user->createToken('auth_token', ['*'], now()->addHours(1))->plainTextToken;
      
      return response()->json([
          'user' => $user,
          'access_token' => $token,
          'token_type' => 'Bearer',
      ]);
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