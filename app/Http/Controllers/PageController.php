<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageComponent;
use App\Models\TextComponent;

class PageController extends Controller
{
    /**
     * Получить список компонентов для указанной страницы.
     *
     * @param  int  $pageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComponents(int $pageId)
    {
        // Получаем компоненты для указанной страницы в обратном порядке
        $pageComponents = PageComponent::where('page_id', $pageId)
            ->orderBy('created_at', 'desc')
            ->with('component') // Загрузка связанных компонентов
            ->get();

        // Преобразуем компоненты в массив с данными
        $components = $pageComponents->map(function ($pageComponent) {
            $componentData = [
                'id' => $pageComponent->id,
                'page_id' => $pageComponent->page_id,
                'type' => $pageComponent->type,
            ];

            // Загружаем данные соответствующего компонента в зависимости от типа
            switch ($pageComponent->type) {
                case 'text':
                    $textComponent = TextComponent::find($pageComponent->component_id);
                    $componentData['text'] = $textComponent ? $textComponent->text : null;
                    break;

                // Добавьте другие типы компонентов здесь, если нужно

                default:
                    $componentData['data'] = null;
                    break;
            }

            return $componentData;
        });

        return $this->sendResponse($components);
    }
}
