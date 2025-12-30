<?php

declare(strict_types=1);

namespace App\Http\Services;

use Throwable;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class ImagesService extends Service
{
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

            // $store = $this->store($img, $data);

            // if (! $store) {
            //     throw new ApiException('store_image_unexpected_error', 500);
            // }

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
            $user = request()->user()->load('images');

            $imageToDelete = $user->images->where('uid', $uid)->first();

            if (! $imageToDelete) {
                return throw new ApiException('image_not_found', 404);
            }

            $delete = Storage::disk('r2')->delete($imageToDelete->url);

            if (! $delete) {
                throw new ApiException('i18n.image_delete_ko', 500);
            }

            $deletedOrder = $imageToDelete->order;
            $imageToDelete->delete();

            $user->images()->where('order', '>', $deletedOrder)->decrement('order');

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

            foreach ($user->images()->get() as $item) {
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
}
