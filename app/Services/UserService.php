<?php
namespace App\Services;

use App\Http\Resources\UserReosurce;
use App\Models\User;
class UserService
{

  public function getUsers(User $user) {
    
    try {
      $users = User::whereNot('id', $user->id)->limit(50)->get();

      return UserReosurce::collection($users);

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