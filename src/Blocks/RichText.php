<?php

namespace Phpsa\FilamentCms\Blocks;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Builder\Block;
use Phpsa\FilamentCms\Components\RichTextEditor;

class RichText
{
    public static function make($field): Block
    {
        return Block::make($field)
            ->schema([
                RichTextEditor::make('rich-text'),
            ])->columnSpan(2);
    }
}
