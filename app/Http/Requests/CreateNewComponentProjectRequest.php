<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewComponentProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'folder_id' => 'nullable|integer',
            'project_id' => 'required|integer|exists:projects,id',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Поле type обязательно.',
            'type.string' => 'Поле type должно быть типа string.',
            'folder_id.integer' => 'Поле folder_id должно быть числом.',
            'project_id.required' => 'Поле project_id обязательно.',
            'project_id.integer' => 'Поле project_id должно быть числом.',
            'project_id.exists' => 'Указанный проект не найден.',
        ];
    }
}
