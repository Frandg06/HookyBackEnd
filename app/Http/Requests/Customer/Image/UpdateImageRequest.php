<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\Image;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|file|image|max:5120|mimes:jpeg,png,jpg,webp',
            'image_uid' => 'required|string|exists:user_images,uid',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'image_uid' => $this->route('uid'),
        ]);
    }
}
