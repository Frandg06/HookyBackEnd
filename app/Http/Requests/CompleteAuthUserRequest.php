<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\User\GenderEnum;
use Illuminate\Validation\Rule;
use App\Enums\User\SexualOrientationEnum;
use Illuminate\Foundation\Http\FormRequest;

final class CompleteAuthUserRequest extends FormRequest
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
            'description' => 'nullable|string',
            'email' => 'required|email',
            'gender' => ['required', 'string', Rule::enum(GenderEnum::class)],
            'name' => 'required|string|min:2',
            'sexual_orientation' => ['required', 'string', Rule::enum(SexualOrientationEnum::class)],
            'surnames' => 'required|string|min:2',
            'userImages' => 'required|array|min:1|max:3',
            'userImages.*' => 'required|file|mimes:jpeg,png,jpg,webp|max:5000',
        ];
    }
}
