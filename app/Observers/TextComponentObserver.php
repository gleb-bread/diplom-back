<?php

namespace App\Observers;

use App\Models\TextComponent;
use App\Models\PageComponent;

class TextComponentObserver
{
    /**
     * Handle the TextComponent "created" event.
     */
    public function created(TextComponent $textComponent): void
    {
        $pageComponent = PageComponent::create([
            'page_id' => $textComponent->page_id,
            'component_id' => $textComponent->id,
            'type' => 'text', // Или любой другой тип, который вам нужен
        ]);

        $textComponent->component_id = $pageComponent->id;
    }

    /**
     * Handle the TextComponent "updated" event.
     */
    public function updated(TextComponent $textComponent): void
    {
        $pageComponent = PageComponent::where([
            ['page_id', '=', $textComponent->page_id],
            ['component_id', '=', $textComponent->id],
            ['type', '=', 'text'],
        ])->first();
    
        if ($pageComponent) {
            // Привязываем временное свойство (не сохранится в БД, но доступно после update)
            $textComponent->component_id = $pageComponent->id;
        }
    }

    /**
     * Handle the TextComponent "deleted" event.
     */
    public function deleted(TextComponent $textComponent): void
    {
        //
    }

    /**
     * Handle the TextComponent "restored" event.
     */
    public function restored(TextComponent $textComponent): void
    {
        //
    }

    /**
     * Handle the TextComponent "force deleted" event.
     */
    public function forceDeleted(TextComponent $textComponent): void
    {
        //
    }
}
