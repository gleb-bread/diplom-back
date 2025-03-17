<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageComponent;
use App\Http\Controllers\TextComponentController;

class PageComponentController extends Controller
{
    public function update(Request $request, $componentId)
    {
        // Находим запись в таблице PageComponent
        $pageComponent = PageComponent::where('component_id', $componentId)->first();

        if (!$pageComponent) {
            return $this->sendError('Component not found!', [], 404);
        }

        // В зависимости от типа выбираем модель и обновляем данные
        switch ($pageComponent->type) {
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
