<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteAuthUserRequest extends FormRequest
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
            'born_date' => 'required|date',
            'city' => 'required|string|min:2',
            'description' => 'nullable|string',
            'email' => 'required|email',
            'gender_id' => 'required|exists:genders,id',
            'ig' => 'nullable|string|min:2',
            'interests' => 'required|string',
            'name' => 'required|string|min:2',
            'sexual_orientation_id' => 'required|exists:sexual_orientations,id',
            'surnames' => 'required|string|min:2',
            'tw' => 'nullable|string|min:2',
        ];
    }
}
