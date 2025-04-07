<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageComponent;
use App\Http\Controllers\TextComponentController;
use App\Http\Requests\ComponentRequest;
use App\Models\TextComponent;
use App\Http\Requests\CreateApiComponentDataRequest;
use App\Models\ApiComponents;
use App\Models\ApiComponentCookie;
use App\Models\ApiComponentParam;
use App\Models\ApiComponentHeader;
use App\Http\Requests\UpdateApiComponentDataRequest;

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
            case TextComponent::$type: {
                $controller = new TextComponentController();
                $result = $controller->update($request);
                $component = $controller->get($request);
                break;
            }

            case ApiComponents::$type: {
                $controller = new ApiComponentController();
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

    public function createApiComponentData(CreateApiComponentDataRequest $request, string $type)
    {
        $validated = $request->validated();

        // Проверяем существует ли ApiComponent с указанным ID
        $apiComponent = ApiComponents::find($validated['api_component_id']);
        
        if (!$apiComponent) {
            return $this->sendError('API Component not found!', [], 404);
        }

        $data = [
            'api_component_id' => $validated['api_component_id'],
            'key' => $validated['key'] ?? '',  // Используем данные из запроса
            'value' => $validated['value'] ?? '', // Используем данные из запроса
        ];

        switch ($type) {
            case ApiComponentParam::$type:
                $param = ApiComponentParam::create($data);
                $param->type = ApiComponentParam::$type;
                break;

            case ApiComponentCookie::$type:
                $param = ApiComponentCookie::create($data);
                $param->type = ApiComponentCookie::$type;
                break;

            case ApiComponentHeader::$type:
                $param = ApiComponentHeader::create($data);
                $param->type = ApiComponentHeader::$type;
                break;

            default:
                return $this->sendError('Undefined type ApiComponentData', [], 400);
        }

        return $this->sendResponse($param, 'Parameter created successfully', 200);
    
    }

    public function updateApiComponentData(UpdateApiComponentDataRequest $request, string $type, $id)
    {
        $validated = $request->validated();

        // Проверяем существует ли ApiComponent с указанным ID
        $apiComponent = ApiComponents::find($validated['api_component_id']);
        
        if (!$apiComponent) {
            return $this->sendError('API Component not found!', [], 404);
        }

        $data = [
            'api_component_id' => $validated['api_component_id'],
            'key' => $validated['key'] ?? '',
            'value' => $validated['value'] ?? '',
        ];

        switch ($type) {
            case ApiComponentParam::$type:
                $param = ApiComponentParam::find($id);
                if (!$param) {
                    return $this->sendError('Parameter not found!', [], 404);
                }
                $param->update($data);
                $param->type = ApiComponentParam::$type;
                break;

            case ApiComponentCookie::$type:
                $param = ApiComponentCookie::find($id);
                if (!$param) {
                    return $this->sendError('Cookie not found!', [], 404);
                }
                $param->update($data);
                $param->type = ApiComponentCookie::$type;
                break;

            case ApiComponentHeader::$type:
                $param = ApiComponentHeader::find($id);
                if (!$param) {
                    return $this->sendError('Header not found!', [], 404);
                }
                $param->update($data);
                $param->type = ApiComponentHeader::$type;
                break;

            default:
                return $this->sendError('Undefined type ApiComponentData', [], 400);
        }

        return $this->sendResponse($param, 'Parameter updated successfully', 200);
    }

    public function deleteApiComponentData(Request $request, string $type, $id)
    {
        switch ($type) {
            case ApiComponentParam::$type:
                $param = ApiComponentParam::find($id);
                if (!$param) {
                    return $this->sendError('Parameter not found!', [], 404);
                }
                $param->delete();
                break;

            case ApiComponentCookie::$type:
                $param = ApiComponentCookie::find($id);
                if (!$param) {
                    return $this->sendError('Cookie not found!', [], 404);
                }
                $param->delete();
                break;

            case ApiComponentHeader::$type:
                $param = ApiComponentHeader::find($id);
                if (!$param) {
                    return $this->sendError('Header not found!', [], 404);
                }
                $param->delete();
                break;

            default:
                return $this->sendError('Undefined type ApiComponentData', [], 400);
        }

        return $this->sendResponse(null, 'Parameter deleted successfully', 200);
    }
}
