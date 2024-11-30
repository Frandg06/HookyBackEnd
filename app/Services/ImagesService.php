<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImagesService {

  public function store(User $user, $img) {


    Log::info($img->getMimeType());
    if($img->getMimeType() !== 'image/jpeg' && $img->getMimeType() !== 'image/png' && $img->getMimeType() !== 'image/webp') {
      throw new \Exception("Solo jpg, png and webp estan permitidos");
    }

    if($img->getSize() > 1024 * 1024 * 10) {
      throw new \Exception("El tamaño de la imagen es muy grande");
    }

    $image = $this->optimize($img);


    DB::beginTransaction();
    try {

        $newImage = $user->userImages()->create([
          'order' => $user->userImages()->count() + 1,
          'name' => $img->getClientOriginalName(),
          'size' => $img->getSize(),
          'type' => $img->getMimeType(),
        ]);

        $storage = Storage::disk('r2')->put($newImage->url, $image);

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
 
  public function delete(User $user, $uid) {
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

  public function deleteAll() {
    try {
      
      foreach (UserImage::all() as $image) {
        $image->delete();
      }
      
      Storage::disk('r2')->deleteDirectory('hooky/profile');

      return true;

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function deleteAllUserImage($user) {
    try {
      foreach($user->userImages()->get() as $item) {
        $item->delete();
      }
      $remove = Storage::disk('r2')->deleteDirectory("hooky/profile/$user->uid");

      if(!$remove) throw new \Exception("Error deleting image");

      return true;
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function optimize($image) {
    $img = Image::read($image);

    $ogWidth = $img->width();
    $ogHeight = $img->height();

    $aspectRatio = $ogWidth / $ogHeight;

    $newHeight = 500 / $aspectRatio;


    return $img->resize(500, $newHeight)->toWebP(80);
  }
}