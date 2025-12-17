<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\TargetUser;

use Illuminate\Foundation\Http\FormRequest;

final class TargetUserRequest extends FormRequest
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
            'target_user_uid' => ['required', 'string', 'exists:users,uid'],
            'event_uid' => ['required', 'string', 'exists:events,uid'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'target_user_uid' => $this->route('target_user_uid'),
            'event_uid' => $this->route('event_uid'),

        ]);
    }
}
