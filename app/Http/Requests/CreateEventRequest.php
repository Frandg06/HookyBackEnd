<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateEventRequest extends FormRequest
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
            'st_date' => 'required|date',
            'end_date' => 'required|date',
            'timezone' => 'required|string',
            'likes' => 'required|integer',
            'super_likes' => 'required|integer',
            'name' => 'required|string|max:255',
            'colors' => 'required|string|max:16',
            'st_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i',
            'code' => 'nullable|string|max:12',
        ];
    }
}
