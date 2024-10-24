<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImagesService {

  public function store(User $user, $img) {

    $imageSize = getimagesize($img);
    $width = $imageSize[0];
    $height = $imageSize[1];
    $size = filesize($img) / 1048576;


    try {
        DB::beginTransaction();

        $newImage = $user->userImages()->create([
          'height' => $height,
          'width' => $width,
          'extension' => $img->getClientOriginalExtension(),
          'order' => $user->userImages()->count() + 1,
          'size' => round($size, 2),
        ]);

        $storage = Storage::disk('r2')->put($newImage->url, file_get_contents($img));

        if(!$storage) {
          throw new \Exception("Error storing image");
        }
        
        DB::commit();

        return true;
        
      } catch (\Exception $e) {
        DB::rollBack();
        throw new \Exception($e->getMessage());
      }
  }

  public function delete($user, $uid) {
    try {
      
      $imageToDelete = $user->userImages()->where('uid', $uid)->first();
      
      if(!$imageToDelete) {
        return throw new \Exception("Image not found");
      }

      $delete = Storage::disk('r2')->delete($imageToDelete->url);
      
      if(!$delete) {
        return throw new \Exception("Error deleting image");
      }
      
      $imageToDelete->delete();
      return true;

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
}