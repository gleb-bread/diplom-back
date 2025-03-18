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
            'type' => 'required|string|in:text,image,video', // Список типов можно расширять
            'component_id' => 'nullable|integer', // Добавляем для совместимости
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Поле id обязательно.',
            'page_id.required' => 'Поле page_id обязательно.',
            'type.required' => 'Поле type обязательно.',
            'type.in' => 'Поле type должно быть одним из: text, image, video.',
        ];
    }
}