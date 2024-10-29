<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserReosurce;
use App\Services\ImagesService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImagesService $imageService) {
        $this->imageService = $imageService;
    }

    public function store(Request $request) {
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5000'
        ]);
        
        $user = $request->user();
        $image = $request->file('image');
        
        if($user->userImages()->count() == 3) return $this->responseError("El usuario ya tiene 3 imágenes");

        try {
            $response = $this->imageService->store($user, $image);
            if(!$response) return $this->responseError("Unexpected error while uploading image");
            return $this->responseSuccess('Image uploaded successfully', UserReosurce::make($user));

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 500);
            
        }
    }

    public function delete(Request $request) {

        $request->validate(["uid" => "required|string"]);

        $uid = $request->uid;
        $user = $request->user();

        try {

            $response = $this->imageService->delete($user, $uid);

            if(!$response) return $this->responseError("Unexpected error while deleting image", 500);

            return $this->responseSuccess('Image delete successfully', UserReosurce::make($user));

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 500);

        }

    }

    public function update(Request $request) {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5000',
            'uid' => 'required|string'
        ]);

        $uid = $request->uid;
        $image = $request->file('image');
        $user = $request->user();

        try {

            $delete = $this->imageService->delete($user, $uid);

            if(!$delete) return $this->responseError("Unexpected error while deleting image", 500);

            $store = $this->imageService->store($user, $image);

            if(!$store) return $this->responseError("Unexpected error while storing new image", 500);

            return $this->responseSuccess('Image swapped successfully', UserReosurce::make($user));

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 500);

        }
    }

    public function deleteAllUserImage(Request $request)  {
        $user = $request->user();

        try{
            
            $response = $this->imageService->deleteAllUserImage($user);

            if(!$response) return $this->responseError("Unexpected error while deleting image", 500);
            
            return $this->responseSuccess('Image delete successfully',  UserReosurce::make($user));

        }catch(\Exception $e) {
            return $this->responseError($e->getMessage(), 500);
        }
    }

    public function deleteAll(Request $request) {
        $user = $request->user();
        
        try {
            $response = $this->imageService->deleteAll();

            return $this->responseSuccess('All images deleted successfully', UserReosurce::make($user));

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 500);

        }
    }
}
