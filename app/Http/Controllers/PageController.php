<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageComponent;
use App\Models\TextComponent;
use App\Services\Component\ComponentType;
use App\Http\Controllers\TextComponentController;
use App\Models\ApiComponents;
use App\Http\Controllers\ApiComponentController;
use App\Models\ApiComponentParam;
use App\Models\ApiComponentCookie;
use App\Models\ApiComponentHeader;
use App\Models\ApiRequest;

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
            ->orderBy('created_at', 'asc')
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
                case TextComponent::$type:{
                    $textComponent = TextComponent::find($pageComponent->component_id);
                    $componentData['text'] = $textComponent ? $textComponent->text : null;
                    $componentData['created_at'] = $textComponent ? $textComponent->created_at : null;
                    $componentData['updated_at'] = $textComponent ? $textComponent->updated_at : null;
                    $componentData['component_id'] = $pageComponent->id;
                    break;
                }

                case ApiComponents::$type: {
                    $apiComponent = ApiComponents::with([
                        'params' => function ($query) {
                            $query->select('id', 'api_component_id', 'key', 'value')->selectRaw("'" . ApiComponentParam::$type . "' as type");
                        },
                        'cookies' => function ($query) {
                            $query->select('id', 'api_component_id', 'key', 'value')->selectRaw("'" . ApiComponentCookie::$type . "' as type");
                        },
                        'headers' => function ($query) {
                            $query->select('id', 'api_component_id', 'key', 'value')->selectRaw("'" . ApiComponentHeader::$type . "' as type");
                        }
                    ])->find($pageComponent->component_id);

                    $apiComponentArray = $apiComponent ? $apiComponent->toArray() : [];

                    // Получаем последнюю запись из ApiComponentRequest
                    $latestRequest = ApiRequest::where('api_component_id', $pageComponent->component_id)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    // Добавляем status и response из последней записи
                    $componentData = array_merge($componentData, $apiComponentArray, [
                        'status' => $latestRequest ? $latestRequest->status : null,
                        'response' => $latestRequest ? $latestRequest->response : null,
                        'component_id' => $pageComponent->id,
                    ]);
                    $componentData['component_id'] = $pageComponent->id;
                    break;
                }

                // Добавьте другие типы компонентов здесь, если нужно

                default: {
                    $componentData['data'] = null;
                    break;
                }
            }

            return $componentData;
        });

        return $this->sendResponse($components);
    }

    public function createComponent(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'page_id' => 'required|integer|exists:pages,id',
            'component_id' => 'required|integer',
            'type' => 'required|string'
        ]);

        $type = $data['type'];

        // Проверяем, существует ли компонент в базе
        if ($data['id'] > 0) return $this->sendError('Component exist', [], 400);

        switch($type){
            case TextComponent::$type: {
                $component = TextComponentController::create($request);
                if(!$component) return $this->sendError('Error at created component', [], 500);
                break;
            }

            case ApiComponents::$type: {  // Новый кейс для API компонента
                $component = ApiComponentController::create($request);
                if (!$component) return $this->sendError('Error at created API component', [], 500);

                // Получаем созданный ApiComponents из ответа контроллера
                $apiComponentId = $component->id ?? $component['id']; // Зависит от структуры ответа ApiComponentController

                // Получаем последнюю запись из ApiComponentRequest
                $latestRequest = ApiRequest::where('api_component_id', $apiComponentId)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Добавляем status и response к возвращаемым данным
                $componentData = $component instanceof \Illuminate\Http\JsonResponse
                    ? $component->getData(true) // Если контроллер возвращает JsonResponse
                    : $component->toArray();    // Если возвращает модель

                $componentData['status'] = $latestRequest ? $latestRequest->status : null;
                $componentData['response'] = $latestRequest ? $latestRequest->response : null;

                return $this->sendResponse($componentData);
            }
    

            default: {
                return $this->sendError('Undefined component', [], 400);
            }
        }

        return $this->sendResponse($component);
    }
}
