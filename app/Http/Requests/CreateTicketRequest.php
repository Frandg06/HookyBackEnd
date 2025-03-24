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
            'count' => 'required|numeric|min:1|max:3000',
            'likes' => 'required|numeric|min:1|max:100',
            'superlikes' => 'required|numeric|min:0|max:100',
            'event_uid' => 'required|string|exists:events,uid',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'count.required' => __('validation.required', ['attribute' => 'count']),
            'count.numeric' => __('validation.numeric', ['attribute' => 'count']),
            'count.min' => __('validation.min.numeric', ['attribute' => 'count', 'min' => 1]),
            'count.max' => __('validation.max.numeric', ['attribute' => 'count', 'max' => 3000]),
            'likes.required' => __('validation.required', ['attribute' => 'likes']),
            'likes.numeric' => __('validation.numeric', ['attribute' => 'likes']),
            'likes.min' => __('validation.min.numeric', ['attribute' => 'likes', 'min' => 1]),
            'likes.max' => __('validation.max.numeric', ['attribute' => 'likes', 'max' => 100]),
            'superlikes.required' => __('validation.required', ['attribute' => 'superlikes']),
            'superlikes.numeric' => __('validation.numeric', ['attribute' => 'superlikes']),
            'superlikes.min' => __('validation.min.numeric', ['attribute' => 'superlikes', 'min' => 0]),
            'superlikes.max' => __('validation.max.numeric', ['attribute' => 'superlikes', 'max' => 100]),
            'event_uid.required' => __('validation.required', ['attribute' => 'event_uid']),
            'event_uid.string' => __('validation.string', ['attribute' => 'event_uid']),
            'event_uid.exists' => __('event_not_found'),
        ];
    }
}
