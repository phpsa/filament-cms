<?php

namespace Phpsa\FilamentCms\Blocks;

use Filament\Forms\Components\Builder\Block;

class Image
{
    public static function make($field): Block
    {
        return Block::make($field)
            ->schema([
                config('filament-cms.uploader.class')::make('content')
                    ->label('Image'),
            ]);
    }
}
