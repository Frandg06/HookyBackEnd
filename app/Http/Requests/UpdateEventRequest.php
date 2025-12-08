<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateEventRequest extends FormRequest
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
            'st_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'timezone' => 'nullable|string',
            'likes' => 'nullable|numeric',
            'super_likes' => 'nullable|numeric',
            'name' => 'nullable|string',
            'colors' => 'nullable|string',
            'st_hour' => 'nullable|date_format:H:i',
            'end_hour' => 'nullable|date_format:H:i',
            'uid' => 'required|string',
            'code' => 'nullable|string|max:12',
        ];
    }
}
