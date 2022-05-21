<?php

namespace Phpsa\FilamentCms\Builders;

use Phpsa\FilamentCms\Blocks\Image;
use Phpsa\FilamentCms\Blocks\Gallery;
use Filament\Forms\Components\Builder;
use Phpsa\FilamentCms\Blocks\RichText;

class BlogPost
{
    public static function make(string $field): Builder
    {
        return  Builder::make($field)
            ->createItemButtonLabel('Add Block')
            ->createItemBetweenButtonLabel('Add Block')
            ->withBlockLabels(true)
            ->minItems(1)
            ->blocks([
                RichText::make('html'),
                Image::make('image'),
                Gallery::make('image'),
                // Grid::make(),
                // Hero::make(),
                // ImageLeft::make(),
                // ImageRight::make(),
                // Detail::make(),
                // Infographic::make(),
                // Code::make(),
            ])->columnSpan(2);
    }
}
