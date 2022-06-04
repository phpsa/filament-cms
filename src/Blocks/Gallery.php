<?php

namespace Phpsa\FilamentCms\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Phpsa\FilamentCms\Components\Fields\MediaPicker;

class Gallery
{
    public static function make($field): Block
    {
        return Block::make($field)
            ->schema([
                Repeater::make('type:gallery')->schema([
                    MediaPicker::make('image')
                ])
            ]);
    }
}
