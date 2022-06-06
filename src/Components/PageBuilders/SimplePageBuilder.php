<?php

namespace Phpsa\FilamentCms\Components\PageBuilders;

use Filament\Forms\Components\Builder;
use Phpsa\FilamentCms\Components\Blocks\HeroBlock;
use Phpsa\FilamentCms\Components\Blocks\ImageBlock;
use Phpsa\FilamentCms\Components\Blocks\RichTextBlock;

class SimplePageBuilder
{
    public static function make(string $field): Builder
    {
        return  Builder::make($field)
            ->required()
            ->minItems(1)
            ->default([
                [
                    'type' => 'Html',
                    'data' => [
                    ]
                ]
            ])
            ->blocks([
                RichTextBlock::make('Page Content'),
                HeroBlock::make('Hero'),
                ImageBlock::make("BAS")
            ])->columnSpan(2);
    }
}
