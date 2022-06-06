<?php

namespace Phpsa\FilamentCms\Components\Fields;

use Filament\Forms\Components\View;

class Separator
{
    public static function make(): View
    {
        return View::make('filament-cms::filament.components.separator');
    }
}
