<?php

namespace Phpsa\FilamentCms\Resources\CmsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Phpsa\FilamentCms\Components\Fields\CmsResource;

class ListCmsPage extends ListRecords
{
    protected static string $resource = CmsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return static::getResource()::getEloquentQuery()->whereNamespace(static::getResource());
    }
}
