<?php

namespace Phpsa\FilamentCms\Components\Fields;

use Filament\Forms\Components\Field;

class RichTextEditor
{
    public static function make(string $field): Field
    {
        $editorConfig = config('filament-cms.editor');

        $editorClass = $editorConfig['class'];
        unset($editorConfig['class']);

        return tap($editorClass::make($field)
            ->columnSpan(2)
            ->disableLabel()
            ->required(), function($editor) use ($editorConfig) {
                foreach($editorConfig as $callable => $value){
                    if(method_exists($editor, $callable)){
                        $editor->$callable($value);
                    }
                }
            });

    }
}
