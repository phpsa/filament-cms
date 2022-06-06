<?php

namespace Phpsa\FilamentCms\Components\Blocks;

use Filament\Forms\Components\Builder\Block;
use Phpsa\FilamentCms\Components\Fields\RichTextEditor;

class RichTextBlock
{
    public static function make(string $label): Block
    {
        return Block::make("Html")
            ->label($label)
            ->schema([
                RichTextEditor::make('content'),
            ])->columnSpan(2);
    }
}
