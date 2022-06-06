<?php

namespace Phpsa\FilamentCms\Components\Blocks;

use Closure;
use Filament\Forms\Components\Builder\Block;
use Phpsa\FilamentCms\Components\Fields\Gallery;

class GalleryBlock
{
    public static function make(string $label): Block
    {
        return Block::make('Gallery')->label($label)
            ->schema([
                Gallery::make('images')->columnSpan(2)->grid(4)->disableLabel()
            ]);
    }
}
