<?php

namespace App\Http\Controllers;

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
        
        try {

            $response = $this->imageService->store($user, $image);
            if(!$response) return $this->responseError("Unexpected error while uploading image");
            return $this->responseSuccess('Image uploaded successfully');

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

            return $this->responseSuccess('Image delete successfully');

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 500);

        }


    }
}
