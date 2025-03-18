<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageComponent;
use App\Http\Controllers\TextComponentController;
use App\Http\Requests\ComponentRequest;

class PageComponentController extends Controller
{
    public function update(ComponentRequest $request)
    {
        $validated = $request->validated();
        // Находим запись в таблице PageComponent
        $pageComponent = PageComponent::find($validated['id']);

        if (!$pageComponent) {
            return $this->sendError('Component not found!', [], 404);
        }

        // Добавляем component_id в объект $request
        $request->merge(['component_id' => $pageComponent->component_id]);

        $type = $validated['type'];

        // В зависимости от типа выбираем модель и обновляем данные
        switch ($type) {
            case 'text': {
                $controller = new TextComponentController();
                $result = $controller->update($request);
                $component = $controller->get($request);
                break;
            }

            default:
                return $this->sendError('Undefined type component!', [], 400);
        }

        if($result instanceof Request) return $result;

        return $this->sendResponse($component);
    }
}
