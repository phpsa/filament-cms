<?php

namespace Phpsa\FilamentCms\Builders;

use Filament\Forms\Components\Builder;
use Phpsa\FilamentCms\Blocks\RichText;

class Simple
{
    public static function make(string $field): Builder
    {
        return  Builder::make($field)
            ->withBlockLabels(false)
            ->createItemButtonLabel(true)
            ->disableItemMovement(true)
            ->disableItemDeletion(true)
            ->disableItemCreation(false)
            ->required()
            ->maxItems(1)
            ->minItems(1)
            ->default([
                [
                    'type' => 'html',
                    'data' => [
                    ]
                ]
            ])
            ->blocks([
                RichText::make('html'),
            ])->columnSpan(2);
    }
}
