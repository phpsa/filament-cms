<?php

namespace Phpsa\FilamentCms\Components\Fields;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class Tabset
{
    public static function make(string $field): Field
    {
        return Repeater::make('TabItems')
                    ->disableLabel()
                    ->createItemButtonLabel('Add Tab')
                    ->schema([
                        TextInput::make('title')->label("Tab")->required(),
                        RichTextEditor::make('tabcontent')->label("Tab Body")->disableLabel(false)->required(false),
                        MarkdownEditor::make('markdown'),
                        Textarea::make('testarea')

                    ]);
    }
}
