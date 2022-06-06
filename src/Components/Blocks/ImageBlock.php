<?php

namespace Phpsa\FilamentCms\Components\Blocks;

use Filament\Forms\Components\Builder\Block;

class ImageBlock
{
    public static function make($label): Block
    {
        return Block::make('Image')->label($label)
            ->schema([
                config('filament-cms.uploader.class')::make('content')->disableLabel()
            ]);
    }
}
