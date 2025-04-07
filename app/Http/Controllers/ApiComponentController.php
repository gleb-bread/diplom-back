<?php

namespace App\Http\Controllers;

use App\Models\ApiComponents;
use Illuminate\Http\Request;
use App\Http\Requests\ComponentRequest;
use App\Http\Requests\ApiComponentRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiComponentController extends Controller
{
    public function update(ComponentRequest $request)
    {
        $validated = $this->validateRequest($request);

        $id = $validated['component_id'];

        // Находим API компонент по ID
        $apiComponent = ApiComponents::find($id);

        if (!$apiComponent) {
            return $this->sendError('API Component not found!', [], 404);
        }

        // Обновляем поля компонента
        $apiComponent->update([
            'name' => $validated['name'] ?? null,
            'method' => $validated['method'] ?? null,
            'url' => $validated['url'] ?? null,
        ]);

        $apiComponent->refresh();

        return $apiComponent;
    }

    public function get(Request $request)
    {
        $validated = $this->validateRequest($request);
        
        $id = $validated['component_id'];
        $apiComponent = ApiComponents::find($id);
        
        if (!$apiComponent) {
            return $this->sendError('API Component not found!', [], 404);
        }

        return $apiComponent;
    }

    public static function create(Request $request): ?ApiComponents
    {
        $data = $request->validate([
            'page_id' => 'required|integer|exists:pages,id',
            'name' => 'nullable|string',
            'method' => 'nullable|string|max:8',
            'url' => 'nullable|string',
        ]);

        $apiComponent = ApiComponents::create([
            'name' => $data['name'] ?? null,
            'method' => $data['method'] ?? null,
            'url' => $data['url'] ?? null,
            'page_id' => $data['page_id'],
        ]);

        return $apiComponent;
    }

    /**
     * @throws ValidationException
     */
    private function validateRequest(Request $request): array
    {
        $apiRequest = new ApiComponentRequest();
        $rules = $apiRequest->rules();
        $data = $request->all();

        $validator = Validator::make($data, $rules, $apiRequest->messages());

        if ($validator->fails()) {
            throw new ValidationException($validator, $this->sendError('Validation failed!', $validator->errors()->all(), 422));
        }

        return $validator->validated();
    }
}
