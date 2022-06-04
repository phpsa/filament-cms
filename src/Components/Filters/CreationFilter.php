<?php

namespace Phpsa\FilamentCms\Components\Filters;

use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Builder;

class CreationFilter
{
    public static function make(): Filter
    {
        return Filter::make('created_at')->form(
            [
                DateTimePicker::make('created_from')
                ->label(strval(__('filament-cms::filament-cms.table.filter.created_from')))
                ->minDate('2020-01-01')
                ->maxDate(now()),
                DateTimePicker::make('created_until')
                ->label(strval(__('filament-cms::filament-cms.table.filter.created_until')))
                ->minDate('2020-01-01')
                ->maxDate(now())
            ]
        )->query(
            fn (Builder $query, array $data): Builder  => $query
                ->when(
                    $data['created_from'],
                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                )
                ->when(
                    $data['created_until'],
                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                )
        );
    }
}
