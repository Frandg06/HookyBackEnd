<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\User;

use Illuminate\Foundation\Http\FormRequest;

final class NotifyStartOfEventRequest extends FormRequest
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
            'event_uid' => ['required', 'string', 'exists:events,uid'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'event_uid' => $this->route('uid'),
        ]);
    }
}
