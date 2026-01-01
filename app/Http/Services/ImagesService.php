<?php

declare(strict_types=1);

namespace App\Http\Services;

use Throwable;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Customer\UserResource;

final class ImagesService extends Service
{
    public function update($img_uid, $img, $data)
    {
        DB::beginTransaction();
        try {
            // $user = $this->user();

            // if ($img_uid) {

            //     $delete = $this->delete($img_uid);

            //     if (! $delete) {
            //         throw new ApiException('delete_image_unexpected_error', 500);
            //     }
            // }

            // // $store = $this->store($img, $data);

            // // if (! $store) {
            // //     throw new ApiException('store_image_unexpected_error', 500);
            // // }

            // DB::commit();

            // return UserResource::make($user->loadRelations());
        } catch (Throwable $e) {
            DB::rollBack();
            debug(['error_updating_image' => $e->getMessage()]);
            throw $e;
        }
    }
}
