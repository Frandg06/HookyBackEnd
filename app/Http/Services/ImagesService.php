<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\UserImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImagesService {

  public function store($img) 
  {
    DB::beginTransaction();
    try {

      $user = request()->user();

      if($user->userImages()->count() == 3) throw new ApiException('user_images_limit', 409);

      if(!in_array($img->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
        throw new ApiException('images_extension_ko', 409);
      }

      if($img->getSize() > 1024 * 1024 * 10) throw new ApiException('images_size_ko', 409);

      $image = $this->optimize($img);

      $newImage = $user->userImages()->create([
        'order' => $user->userImages()->count() + 1,
        'name' => $img->getClientOriginalName(),
        'size' => $img->getSize(),
        'type' => $img->getMimeType(),
      ]);
      
      $storage = Storage::disk('r2')->put($newImage->url, $image);
      if(!$storage) throw new ApiException('images_store_ko', 500); 
      
      DB::commit();

      return $user->resource();
        
    } catch (ApiException $e) {
      DB::rollBack();
      throw new ApiException($e->getMessage(), $e->getCode());
    }catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new ApiException('images_store_ko', 500);
    }
  }

  public function update($img_uid, $img)
  {
    DB::beginTransaction();
    try {
      $user = request()->user();

      $delete = $this->delete($img_uid);

      if(!$delete) throw new ApiException('delete_image_unexpected_error', 500);
      
      $store = $this->store($img);

      if(!$store) throw new ApiException('store_image_unexpected_error', 500);
      
      DB::commit();

      return $user->resource();
      
    } catch (ApiException $e) {
      DB::rollBack();
      throw new ApiException($e->getMessage(), $e->getCode());
    }catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new ApiException('images_store_ko', 500);
    }
  }
 
  public function delete($uid) 
  {
    try {

      $user = request()->user();
      
      $imageToDelete = $user->userImages()->where('uid', $uid)->first();
      
      if(!$imageToDelete) return throw new ApiException('image_not_found', 404);

      $delete = Storage::disk('r2')->delete($imageToDelete->url);
      
      if(!$delete) throw new ApiException('i18n.image_delete_ko', 500);
      
      $imageToDelete->delete();
      
      return true;

    } catch (ApiException $e) {
      DB::rollBack();
      throw new ApiException($e->getMessage(), $e->getCode());
    }catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new ApiException('images_store_ko', 500);
    }
  }

  public function deleteUserImages() 
  {
    DB::beginTransaction();
    try {
      $user = request()->user();
  
      foreach($user->userImages()->get() as $item) {
        $item->delete();
      }

      $remove = Storage::disk('r2')->deleteDirectory("hooky/profile/$user->uid");

      if(!$remove) throw new ApiException('image_delete_ko', 500);

      return true; 
    } catch (ApiException $e) {
      DB::rollBack();
      throw new ApiException($e->getMessage(), $e->getCode());
    }catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new ApiException('image_delete_ko', 500);
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