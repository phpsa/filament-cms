<?php

namespace Phpsa\FilamentCms\Components\Fields;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;

class Gallery
{
    public static function make(string $field): Field
    {
        $uploader = config('filament-cms.uploader');

        return Repeater::make($field)->schema([
            $uploader['class']::make('image')
        ])->collapsible();
    }
}
