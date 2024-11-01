<?php
namespace App\Services;

use App\Http\Resources\UserReosurce;
use App\Models\User;
class UserService
{

  public function getUsers(User $user) {
    
    try {
      $users = User::whereNot('id', $user->id)->limit(10)->paginate(10);

      


      
      
      return [
        "userToSee" => UserReosurce::collection($users),
        "links" => [
          "nextUrl" => $users->nextPageUrl(),
          "prevUrl" => $users->previousPageUrl(),
          "nextPage" => ($users->currentPage() + 1) > $users->lastPage() ? null : $users->currentPage() + 1,
          "prevPage" => ($users->currentPage() - 1) < 1 ? null : $users->currentPage() - 1
        ]
    ];

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