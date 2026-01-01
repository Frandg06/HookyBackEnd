<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\User;

use Illuminate\Foundation\Http\FormRequest;

final class ShowTargetUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_uid' => $this->route('uid'),
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
            'user_uid' => 'required|string|exists:users,uid',
        ];
    }
}
