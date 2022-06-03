<?php

namespace Phpsa\FilamentCms\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class Gallery
{
    public static function make($field): Block
    {
        return Block::make($field)
            ->schema([
                FileUpload::make('content')
                    ->label('Gallery')
                    ->multiple()
               //         ->collection('gallery')
                        ->enableReordering()
                        ->panelLayout('grid'),
            ]);
    }
}
