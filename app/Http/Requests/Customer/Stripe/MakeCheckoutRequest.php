<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer\Stripe;

use Illuminate\Foundation\Http\FormRequest;

final class MakeCheckoutRequest extends FormRequest
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
            'price_id' => $this->route('price_id'),
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
            'price_id' => 'required|string|exists:pricing_plans,product_id',
        ];
    }
}
