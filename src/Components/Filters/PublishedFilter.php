<?php

namespace Phpsa\FilamentCms\Components\Filters;

use Filament\Tables\Filters\MultiSelectFilter;

class PublishedFilter
{
    public static function make(): MultiSelectFilter
    {
        return MultiSelectFilter::make('status')
            ->label(strval(__('filament-cms::filament-cms.table.filter.status')))
            ->options(config('filament-cms.statusEnum')::toArray());
    }
}
