<?php

namespace App\Http\Controllers;

use App\Http\Services\ImagesService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImagesService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5000'
        ]);

        $image = $request->file('image');
        $original_data = [
            'width' => $request->width,
            'height' => $request->height,
        ];

        $response = $this->imageService->store($image, $original_data);

        return response()->json(['success' => true, 'resp' =>  $response], 200);
    }

    public function delete(string $uid)
    {
        $this->imageService->delete($uid);

        return $this->response($this->user()->resource());
    }

    public function update(Request $request)
    {

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5000',
            'uid' => 'nullable|string'
        ]);

        $uid = $validated['uid'] ?? null;
        $image = $request->file('image');
        $original_data = [
            'width' => $request->width,
            'height' => $request->height,
        ];


        $response = $this->imageService->update($uid, $image, $original_data);
        return response()->json(['success' => true, 'resp' =>  $response], 200);
    }

    public function deleteUserImages()
    {
        $response = $this->imageService->deleteUserImages();
        return response()->json(['success' => true, 'resp' =>  'Image delete successfully'], 200);
    }

    public function deleteAll()
    {
        try {
            $response = $this->imageService->deleteAll();
            return response()->json(['success' => true, 'resp' =>  'All images deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }
}
