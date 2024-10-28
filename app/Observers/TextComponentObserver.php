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
        PageComponent::create([
            'page_id' => $textComponent->page_id,
            'component_id' => $textComponent->id,
            'type' => 'text', // Или любой другой тип, который вам нужен
        ]);
    }

    /**
     * Handle the TextComponent "updated" event.
     */
    public function updated(TextComponent $textComponent): void
    {
        //
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
