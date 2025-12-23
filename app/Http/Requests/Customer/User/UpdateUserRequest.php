<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\User;

use App\Enums\User\GenderEnum;
use Illuminate\Validation\Rule;
use App\Enums\User\SexualOrientationEnum;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->uid, 'uid')],
            'born_date' => ['required', 'date', 'before:-18 years'],
            'gender' => ['required', 'string', Rule::enum(GenderEnum::class)],
            'sexual_orientation' => ['required', 'string', Rule::enum(SexualOrientationEnum::class)],
            'description' => ['nullable', 'string'],
        ];
    }
}
