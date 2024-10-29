<? 
namespace App\Services;

use App\Models\User;
class UserService
{

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