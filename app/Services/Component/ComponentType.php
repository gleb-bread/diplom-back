<?php

namespace App\Services\Component;

use Illuminate\Http\Request;
use App\Models\TextComponent;

class ComponentType {

    /**
     * Определяет тип компонента на основе входящего запроса.
     *
     * @param Request $request
     * @return string|null
     */
    public static function getTypeByRequest(Request $request): ?string
    {
        // Карта типов компонентов с их уникальными полями
        $componentMap = [
            TextComponent::$type => TextComponent::$typeFields,
        ];

        // Получаем поля из запроса
        $requestFields = array_keys($request->all());

        // Проверяем, какой тип больше всего совпадает
        $matchedType = null;
        $matchedCount = 0;

        foreach ($componentMap as $type => $fields) {
            $count = count(array_intersect($fields, $requestFields));

            if ($count > $matchedCount) {
                $matchedType = $type;
                $matchedCount = $count;
            }
        }

        return $matchedType;
    }

}

?>