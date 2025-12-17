<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Spatie\Image\Image;
use Throwable;

final class ImagesService extends Service
{
    public function store($file)
    {
        DB::beginTransaction();
        try {
            $user = request()->user();

            if ($user->userImages()->count() === 3) {
                throw new ApiException('user_images_limit', 409);
            }

            $tempPath = sys_get_temp_dir().'/'.uniqid().'.webp';

            Image::load($file->getPathname())
                ->width(500)
                ->format('webp')
                ->optimize()
                ->save($tempPath);

            $newImage = $user->userImages()->create([
                'order' => $user->userImages()->count() + 1,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
            ]);

            $stream = fopen($tempPath, 'r');
            $storage = Storage::disk('r2')->put($newImage->url, $stream);

            if (! $storage) {
                throw new ApiException('images_store_ko', 500);
            }

            if (is_resource($stream)) {
                fclose($stream);
            }

            DB::commit();

            return $user->toResource();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($img_uid, $img, $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->user();

            if ($img_uid) {

                $delete = $this->delete($img_uid);

                if (! $delete) {
                    throw new ApiException('delete_image_unexpected_error', 500);
                }
            }

            $store = $this->store($img, $data);

            if (! $store) {
                throw new ApiException('store_image_unexpected_error', 500);
            }

            DB::commit();

            return $user->toResource();
        } catch (Throwable $e) {
            DB::rollBack();
            debug(['error_updating_image' => $e->getMessage()]);
            throw $e;
        }
    }

    public function delete($uid)
    {
        DB::beginTransaction();
        try {
            $user = request()->user();

            $imageToDelete = $user->userImages()->where('uid', $uid)->first();

            if (! $imageToDelete) {
                return throw new ApiException('image_not_found', 404);
            }

            $delete = Storage::disk('r2')->delete($imageToDelete->url);

            if (! $delete) {
                throw new ApiException('i18n.image_delete_ko', 500);
            }

            $imageToDelete->delete();
            DB::commit();

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteUserImages()
    {
        DB::beginTransaction();
        try {
            $user = request()->user();

            foreach ($user->userImages()->get() as $item) {
                $item->delete();
            }

            $remove = Storage::disk('r2')->deleteDirectory("hooky/profile/$user->uid");

            if (! $remove) {
                throw new ApiException('image_delete_ko', 500);
            }

            DB::commit();

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error en '.__CLASS__.'->'.__FUNCTION__, ['exception' => $e]);
            throw new ApiException('image_delete_ko', 500);
        }
    }

    private function optimize($image, $data)
    {

        // $manager = new ImageManager(
        //     Driver::class,
        //     autoOrientation: false,
        //     strip: true
        // );

        return Image::load($image->getOriginalPath())->resize(250, 200)->optimize();

        // $img = $manager->read($image);

        // $rotate = 0;

        // $size = $img->size();
        // $ratio = $size->aspectRatio();

        // if ($data['width'] === $img->height() && $ratio !== 1) {
        //     $rotate = -90;

        // return $img->scale(width: 500)->rotate($rotate)->toWebP(80);
    }
}
