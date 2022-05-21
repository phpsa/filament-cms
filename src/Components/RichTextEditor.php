<?php

namespace Phpsa\FilamentCms\Components;

use Filament\Forms\Components\Field;

class RichTextEditor
{
    public static function make(string $field): Field
    {
        $editorConfig = config('filament-cms.editor');

        /** @var \Filament\Forms\Components\RichEditor $editor*/
        return $editorConfig['class']::make($field)
            ->columnSpan(2)
            ->disableLabel()
            ->required()
            ->disableAllToolbarButtons($editorConfig['disableAllToolbarButtons'])
            ->enableToolbarButtons($editorConfig['enabledToolbarButtons'])
            ->disableToolbarButtons($editorConfig['disableToolbarButtons']);
    }
}
