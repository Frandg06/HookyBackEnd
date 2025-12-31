<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\User\Notification;

use Illuminate\Validation\Rule;
use App\Enums\NotificationTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

final class ReadNotificationsByTypeRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::enum(NotificationTypeEnum::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'type' => $this->route('type'),
        ]);
    }
}
