<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextComponent;
use App\Http\Requests\ComponentRequest;
use App\Http\Requests\TextComponentRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TextComponentController extends Controller
{
    public function update(ComponentRequest $request)
    {
        $validated = $this->validateRequest($request);

        $id = $validated['component_id'];

        $textComponent = TextComponent::find($id);

        if (!$textComponent) {
            return $this->sendError('Component not found!', [], 404);
        }

        $textComponent->update(['text' => $validated['text']]);

        return $textComponent;
    }

    public function get(Request $request)
    {
        $validated = $this->validateRequest($request);
        
        $id = $validated['component_id'];
        $textComponent = TextComponent::find($id);
        return $textComponent;
    }

    public static function create(Request $request): ?TextComponent
    {
        $data = $request->validate([
            'page_id' => 'required|integer|exists:pages,id',
        ]);

        $textComponent = TextComponent::create([
            'text' => $data['text'] ?? '',
            'page_id' => $data['page_id'],
        ]);

        return $textComponent;
    }

    /**
     * @throws ValidationException
     */
    private function validateRequest(Request $request): array
    {
        $textRequest = new TextComponentRequest();
        $rules = $textRequest->rules();
        $data = $request->all();

        $validator = Validator::make($data, $rules, $textRequest->messages());

        if ($validator->fails()) {
            throw new ValidationException($validator, $this->sendError('Validation failed!', $validator->errors()->all(), 422));
        }

        return $validator->validated();
    }
}