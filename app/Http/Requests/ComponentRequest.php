<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComponentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'page_id' => 'required|integer',
            'type' => 'required|string',
            'component_id' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Поле id обязательно.',
            'page_id.required' => 'Поле page_id обязательно.',
            'type.required' => 'Поле type обязательно.',
        ];
    }
}