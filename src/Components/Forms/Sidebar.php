<?php

namespace Phpsa\FilamentCms\Components\Forms;

use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Contracts\CanEntangleWithSingularRelationships;
use Filament\Forms\Components\Concerns\EntanglesStateWithSingularRelationship;
use Filament\Forms\Components\Section;

class Sidebar
{
    public static function make(
        array|Closure $mainComponents,
        array|Closure $sidebarComponents,
        array|int|string|null $pageColumns = null,
        array|int|string|null $mainSectionColumnSpan = null,
        array|int|string|null $sideBarColumnSpan = null
    ): Grid {

        $pageColumns ??= config('filament-cms.sidebar.page_columns', ['sm' => 1, 'md' => 3, 'xl' => 4]);
        $mainSectionColumnSpan ??= config('filament-cms.sidebar.main_section', ['sm' => 1, 'md' => 2, 'xl' => 3]);
        $sideBarColumnSpan ??= config('filament-cms.sidebar.sidebar', ['sm' => 1]);

        return Grid::make($pageColumns)->schema([
            Grid::make()->schema($mainComponents)->columnSpan($mainSectionColumnSpan),
            Grid::make()->schema($sidebarComponents)->columnSpan($sideBarColumnSpan),
        ]);
    }
}
