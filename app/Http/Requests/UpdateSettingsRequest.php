<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_like_notification' => 'required|boolean',
            'new_superlike_notification' => 'required|boolean',
            'new_message_notification' => 'required|boolean',
            'event_start_email' => 'required|boolean',
        ];
    }
}
