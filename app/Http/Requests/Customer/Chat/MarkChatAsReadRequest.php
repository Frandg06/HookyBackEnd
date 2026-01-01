<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\Chat;

use Illuminate\Foundation\Http\FormRequest;

final class MarkChatAsReadRequest extends FormRequest
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
            'chat_uid' => ['required', 'string', 'exists:chats,uid'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'chat_uid' => $this->route('uid'),
        ]);
    }
}
