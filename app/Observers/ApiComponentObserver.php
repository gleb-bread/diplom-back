<?php

namespace App\Observers;

use App\Models\ApiComponents;
use App\Models\PageComponent;

class ApiComponentObserver
{
    /**
     * Handle the ApiComponent "created" event.
     */
    public function created(ApiComponents $apiComponent): void
    {
        $pageComponent = PageComponent::create([
            'page_id' => $apiComponent->page_id,
            'component_id' => $apiComponent->id,
            'type' => 'api',
        ]);
    
        // Присваиваем временно pageComponent в модель
        $apiComponent->component_id = $pageComponent->id;
    }

    /**
     * Handle the ApiComponent "updated" event.
     */
    public function updated(ApiComponents $apiComponent): void
    {
        $pageComponent = PageComponent::where([
            ['page_id', '=', $apiComponent->page_id],
            ['component_id', '=', $apiComponent->id],
            ['type', '=', 'api'],
        ])->first();
    
        if ($pageComponent) {
            // Привязываем временное свойство (не сохранится в БД, но доступно после update)
            $apiComponent->component_id = $pageComponent->id;
        }
    }

    /**
     * Handle the ApiComponent "deleted" event.
     */
    public function deleted(ApiComponents $apiComponent): void
    {
        // Удаляем компонент из PageComponent при удалении ApiComponent
        PageComponent::where('component_id', $apiComponent->id)
                     ->where('page_id', $apiComponent->page_id)
                     ->delete();
    }

    /**
     * Handle the ApiComponent "restored" event.
     */
    public function restored(ApiComponents $apiComponent): void
    {
        // Если вы поддерживаете восстановление компонента, можно здесь добавить логику
        // Например, восстановить запись в PageComponent, если она была удалена
    }

    /**
     * Handle the ApiComponent "force deleted" event.
     */
    public function forceDeleted(ApiComponents $apiComponent): void
    {
        // Если компонент был жестко удален, можно удалить запись из PageComponent
        PageComponent::where('component_id', $apiComponent->id)
                     ->where('page_id', $apiComponent->page_id)
                     ->delete();
    }
}
