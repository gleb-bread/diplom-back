<?php

namespace App\Http\Requests;

class TextComponentRequest extends ComponentRequest
{
    public function rules(): array
    {
        // Получаем правила из родительского класса
        $parentRules = parent::rules();

        // Добавляем или модифицируем правила
        $newRules = [
            'text' => 'required|string',
        ];

        // Объединяем правила
        return array_merge($parentRules, $newRules);
    }

    // Опционально: расширяем сообщения об ошибках
    public function messages(): array
    {
        $parentMessages = parent::messages();

        $newMessages = [
            'text.required' => 'Поле text обязательно.',
            'text.string' => 'Поле text должно быть string.',
        ];

        return array_merge($parentMessages, $newMessages);
    }
}