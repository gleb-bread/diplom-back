<?php

namespace App\Http\Requests;

class ApiComponentRequest extends ComponentRequest
{
    public function rules(): array
    {
        // Получаем правила из родительского класса
        $parentRules = parent::rules();

        // Добавляем или модифицируем правила для полей API компонента
        $newRules = [
            'name' => 'nullable|string|max:255', // Название компонента (необязательно, строка)
            'method' => 'nullable|string|in:GET,POST,PUT,DELETE', // Метод запроса (необязательно, одна из допустимых строк)
            'url' => 'nullable|string|url', // URL (необязательно, строка в формате URL)
        ];

        // Объединяем правила
        return array_merge($parentRules, $newRules);
    }

    // Опционально: расширяем сообщения об ошибках
    public function messages(): array
    {
        $parentMessages = parent::messages();

        $newMessages = [
            'name.nullable' => 'Поле name может быть пустым.',
            'name.string' => 'Поле name должно быть строкой.',
            'name.max' => 'Поле name не может превышать 255 символов.',
            'method.nullable' => 'Поле method может быть пустым.',
            'method.string' => 'Поле method должно быть строкой.',
            'method.in' => 'Поле method должно быть одним из: GET, POST, PUT, DELETE.',
            'url.nullable' => 'Поле url может быть пустым.',
            'url.string' => 'Поле url должно быть строкой.',
            'url.url' => 'Поле url должно содержать правильный URL.',
        ];

        return array_merge($parentMessages, $newMessages);
    }
}
