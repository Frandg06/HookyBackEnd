<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteDataRequest extends FormRequest
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
            'instagram' => 'nullable|string',
            'twitter' => 'nullable|string',
            'description' => 'nullable|string',
            'city' => 'required|string',
            'born_date' => 'required|date',
            'gender_id' => 'required|integer|exists:genders,id',
            'sexual_orientation_id' => 'required|integer|exists:sexual_orientations,id',
        ];
    }
}
