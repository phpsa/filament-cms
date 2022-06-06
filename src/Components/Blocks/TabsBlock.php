<?php

namespace Phpsa\FilamentCms\Components\Blocks;

use Filament\Forms\Components\Builder\Block;
use Phpsa\FilamentCms\Components\Fields\Tabset;

class TabsBlock
{
    public static function make(string $label): Block
    {
        return Block::make('Tabs')->label($label)
            ->schema([
                Tabset::make('tab')
            ]);
    }
}
