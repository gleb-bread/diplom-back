<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextComponent;

class TextComponentController extends Controller
{
    public function update(Request $request)
    {
        $id = $request['id'];

        if(!isset($id)) return $this->sendError('Field id not defined!', [], 400);

        // Валидация входных данных
        $data = $request->validate([
            'text' => 'required|string',
        ]);

        // Находим компонент
        $textComponent = TextComponent::find($id);

        if (!$textComponent) {
            return $this->sendError('Component not found!', [], 404);
        }

        // Обновляем текст
        $textComponent->update(['text' => $data['text']]);

        return $textComponent;
    }

    public function get(Request $request)
    {
        $id = $request['id'];

        $textComponent = TextComponent::find($id);

        return $textComponent;
    }

    public static function create(Request $request): ?TextComponent {
        // Валидация входящих данных
        $data = $request->validate([
            'page_id' => 'required|integer|exists:pages,id',
        ]);
    
        // Создание нового компонента
        $textComponent = TextComponent::create([
            'text' => $data['text'] ?? '',
            'page_id' => $data['page_id'],
        ]);
    
        return $textComponent;
    }
}
