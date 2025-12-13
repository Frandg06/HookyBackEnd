<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\Image;

use Illuminate\Foundation\Http\FormRequest;

final class ImageOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'image_uid' => $this->route('uid'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image_uid' => 'required|string|exists:user_images,uid',
            'direction' => 'required|string|in:up,down',
        ];
    }
}
