<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages;

use Filament\Resources\Pages\ListRecords as FilamentListRecords;
use Illuminate\Database\Eloquent\Builder;
use Phpsa\FilamentCms\Resources\PagesResource;

class ListRecords extends FilamentListRecords
{
    protected static string $resource = PagesResource::class;

    protected function getTableQuery(): Builder
    {
        return static::getResource()::getEloquentQuery()->whereNamespace(static::getResource());
    }
}
