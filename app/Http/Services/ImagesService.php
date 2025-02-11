<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Models\UserImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImagesService {

  public function store(User $user, $img) 
  {

    if($img->getMimeType() !== 'image/jpeg' && $img->getMimeType() !== 'image/png' && $img->getMimeType() !== 'image/webp') {
      throw new ApiException(__('i18n.images_extension_ko')); 
    }

    if($img->getSize() > 1024 * 1024 * 10) throw new ApiException(__('i18n.images_size_ko'));

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

        if(!$storage) throw new ApiException(__('i18n.images_store_ko'));
        
        DB::commit();

        return true;
        
      } catch (ApiException $e) {
        DB::rollBack();
        throw new \Exception($e->getMessage());
      }catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
        throw new \Exception(__('i18n.images_store_ko'));
      }
  }
 
  public function delete(User $user, $uid) 
  {
    try {
      
      $imageToDelete = $user->userImages()->where('uid', $uid)->first();
      
      if(!$imageToDelete) {
        return throw new ApiException(__('i18n.image_not_found'));
      }

      $delete = Storage::disk('r2')->delete($imageToDelete->url);
      
      if(!$delete) throw new ApiException(__('i18n.image_delete_ko'));
      
      $imageToDelete->delete();
      
      return true;

    } catch (ApiException $e) {
      throw new \Exception($e->getMessage());
    } catch (\Exception $e) {
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new \Exception(__('i18n.image_delete_ko'));
    }
  }

  public function deleteAll() 
  {
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

  public function deleteUserImages() 
  {
    $user = request()->user();
    try {
      foreach($user->userImages()->get() as $item) {
        $item->delete();
      }
      $remove = Storage::disk('r2')->deleteDirectory("hooky/profile/$user->uid");

      if(!$remove) throw new ApiException(__('i18n.image_delete_ko'));

      return true; 
    } catch (ApiException $e) {
      throw new \Exception($e->getMessage());
    } catch (\Exception $e) {
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new \Exception(__('i18n.image_delete_ko'));
    }
  }

  private function optimize($image) 
  {
    $img = Image::read($image);

    $ogWidth = $img->width();
    $ogHeight = $img->height();

    $aspectRatio = $ogWidth / $ogHeight;

    $newHeight = 500 / $aspectRatio;


    return $img->resize(500, $newHeight)->toWebP(80);
  }
  
}