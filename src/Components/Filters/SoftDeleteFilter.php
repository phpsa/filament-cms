<?php

namespace Phpsa\FilamentCms\Components\Filters;

use Filament\Tables\Filters\SelectFilter;

class SoftDeleteFilter
{
    public static function make(): SelectFilter
    {
        return SelectFilter::make('deleted_at')
            ->options([
                'withoutTrashed' => __('filament-cms::filament-cms.table.filter.active'),
                'onlyTrashed'    => __('filament-cms::filament-cms.table.filter.deleted'),
            ])
            ->label(__('filament-cms::filament-cms.table.filter.trashed'))
            ->default('without-trashed')
            ->query(
                fn ($builder, $filter) => match ($filter['value']) {
                    'withoutTrashed' => $builder->withoutTrashed(),
                    'onlyTrashed'    => $builder->onlyTrashed(),
                    default    => $builder->withTrashed(),
                }
            );
    }
}
