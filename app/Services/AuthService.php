<?php
namespace App\Services;

use App\Http\Resources\UserReosurce;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService {

    public function register($data) {

      $companyDefaultData = [
        'like_credits' => 20,
        'super_like_credits' => 3,
      ];

      $paseData = array_merge($companyDefaultData, $data);

      $user = User::create($paseData);

      $token = $user->createToken('auth_token', ['*'], now()->addHours(1))->plainTextToken;

      return (object)[
          'user' => $user,
          'access_token' => $token,
      ];
        
    }

    public function login($data) {

      if (!Auth::attempt($data)) {
        throw new \Exception('Invalid credentials');
      }

      $user = User::with('userImages')->where('email', $data['email'])->get()->firstOrFail();
      $token =  $user->createToken('auth_token', ['*'], now()->addHours(1))->plainTextToken;
      return (object)[
          'user' => UserReosurce::make($user),
          'access_token' => $token,
      ];
    }

    public function update(User $user, $data) {
      try {
        
        $socials = $data['socials'] ?? [];
        $user->update($data);
        
        return UserReosurce::make($user);

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