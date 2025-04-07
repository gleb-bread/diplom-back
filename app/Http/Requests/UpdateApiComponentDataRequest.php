<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ApiComponentParam;
use App\Models\ApiComponentCookie;
use App\Models\ApiComponentHeader;

class UpdateApiComponentDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Измените на свою логику авторизации, если требуется
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Получаем тип из URL (param, cookie, header)
        $type = $this->route('type');

        return [
            'api_component_id' => [
                'required',
                'integer',
                Rule::exists('api_components', 'id'), // Проверяем существование в таблице api_components
            ],
            'key' => [
                'required',
                'string',
                'max:255',
            ],
            'value' => [
                'required',
                'string',
                'max:255',
            ],
            // Валидация типа из URL
            'type' => [
                Rule::in([
                    ApiComponentParam::$type,
                    ApiComponentCookie::$type,
                    ApiComponentHeader::$type,
                ]),
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'api_component_id.required' => 'API Component ID is required.',
            'api_component_id.integer' => 'API Component ID must be an integer.',
            'api_component_id.exists' => 'The specified API Component does not exist.',
            'key.required' => 'Key is required.',
            'key.string' => 'Key must be a string.',
            'key.max' => 'Key may not be longer than 255 characters.',
            'value.required' => 'Value is required.',
            'value.string' => 'Value must be a string.',
            'value.max' => 'Value may not be longer than 255 characters.',
            'type.in' => 'Type must be one of: param, cookie, header.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Добавляем type из URL в данные для валидации
        $this->merge([
            'type' => $this->route('type'),
        ]);
    }
}