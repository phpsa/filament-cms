<?php

namespace Phpsa\FilamentCms\Blocks;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Builder\Block;

class RichText
{
    public static function make(): Block
    {
        return Block::make('rich-text')
            ->schema([
                static::buildEditor('rich-text'),
            ]);
    }

    protected static function buildEditor(string $field): Field
    {
        $editorConfig = config('filament-cms.editor');

        /** @var \Filament\Forms\Components\RichEditor $editor*/
        $editor = $editorConfig['class']::make($field)
            ->columnSpan(2)
            ->disableLabel()
            ->required()
            ->disableAllToolbarButtons($editorConfig['disableAllToolbarButtons'])
            ->enableToolbarButtons($editorConfig['enabledToolbarButtons'])
            ->disableToolbarButtons($editorConfig['disableToolbarButtons']);

        return $editor;
    }
}
