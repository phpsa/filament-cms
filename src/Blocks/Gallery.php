<?php

namespace Phpsa\FilamentCms\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class Gallery
{
    public static function make(): Block
    {
        return Block::make('Gallery')
            ->schema([
                SpatieMediaLibraryFileUpload::make('gallery')
                    ->label('Gallery')
                    ->multiple()
                        ->collection('gallery')
                        ->enableReordering()
                        ->panelLayout('grid'),
            ]);
    }
}
