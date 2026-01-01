<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\ImagesService;

final class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImagesService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function update(Request $request, string $uid)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5000',
            'uid' => 'nullable|string',
        ]);

        $image = $validated['image'];
        $original_data = [
            'width' => $request->width,
            'height' => $request->height,
        ];

        $response = $this->imageService->update($uid, $image, $original_data);

        return response()->json(['success' => true, 'resp' => $response], 200);
    }

    public function deleteUserImages()
    {
        $response = $this->imageService->deleteUserImages();

        return response()->json(['success' => true, 'resp' => 'Image delete successfully'], 200);
    }
}
