<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\Event;

use Illuminate\Foundation\Http\FormRequest;

final class EventAttachByCompanyRequest extends FormRequest
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
            'company_uid' => ['required', 'uuid', 'exists:companies,uid'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_uid.required' => __('i18n.link_not_valid'),
            'company_uid.uuid' => __('i18n.link_not_valid'),
            'company_uid.exists' => __('i18n.link_not_valid'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'company_uid' => $this->route('uid'),
        ]);
    }
}
