<?php

namespace Phpsa\FilamentCms\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class Image
{
    public static function make(): Block
    {
        return Block::make('Image')
            ->schema([
                SpatieMediaLibraryFileUpload::make('image')
                    ->label('Image'),
            ]);
    }
}
