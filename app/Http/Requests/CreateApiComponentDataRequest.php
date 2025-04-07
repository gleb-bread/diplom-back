<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateApiComponentDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Настройте авторизацию по вашим требованиям
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'api_component_id' => 'required|integer|exists:api_components,id',
            'type' => 'required|string|in:param,header,cookie',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'api_component_id.required' => 'API Component ID is required',
            'api_component_id.exists' => 'API Component does not exist',
            'type.required' => 'Type is required',
            'type.in' => 'Type must be one of: param, header, cookie',
        ];
    }
}