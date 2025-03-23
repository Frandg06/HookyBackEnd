<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'count' => 'required|numeric|min:1|max:1000',
            'likes' => 'required|numeric|min:1|max:100',
            'superlikes' => 'required|numeric|min:0|max:100',
            'event_uid' => 'required|string'
        ];
    }
}
