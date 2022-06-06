<?php

namespace Phpsa\FilamentCms\Components\PageBuilders;

use Filament\Forms\Components\Builder;
use Phpsa\FilamentCms\Components\Blocks\GalleryBlock;
use Phpsa\FilamentCms\Components\Blocks\ImageBlock;
use Phpsa\FilamentCms\Components\Blocks\RichTextBlock;
use Phpsa\FilamentCms\Components\Blocks\TabsBlock;

class PageBuilder
{
    public static function make(string $field): Builder
    {
        return  Builder::make($field)
            ->required()
            ->minItems(1)
            ->blocks([
                RichTextBlock::make('Page Content'),
                ImageBlock::make('Left Image'),
                GalleryBlock::make('Gallery')
             //   TabsBlock::make('tabset'), //buggy at the momment
            ])->columnSpan(2);
    }
}
