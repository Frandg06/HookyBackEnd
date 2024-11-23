<?php
namespace App\Services;

use App\Http\Resources\AuthUserReosurce;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthUserService {

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

    public function setEvent($request) {
      
      if(!$request->user()){
        throw new \Exception("The user not exist");
      }

      $request->user()->company_id = $request->company_id;
      $request->user()->save();

      return $request->user()->company_id;
    }

    public function updatePassword($data, $user) {
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

    public function updateInterest(User $user, $newInterests) {
    
      try {
  
        $hasInterest = $user->interests()->get()->pluck('interest_id')->toArray();
  
        foreach ($newInterests as $item) {
          if(!in_array($item, $hasInterest)) {
            $user->interests()->create([
              'interest_id' => $item
            ]);
          }
        }
  
        foreach ($hasInterest as $item) {
          if(!in_array($item, $newInterests)) {
            $user->interests()->where('interest_id', $item)->delete();
          }
        }
  
        return $user;
        
      } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
      }
  
    }
}