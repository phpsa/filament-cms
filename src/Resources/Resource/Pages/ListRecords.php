<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Filament\Resources\Pages\ListRecords as FilamentListRecords;
use Illuminate\Database\Eloquent\Builder;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Phpsa\FilamentCms\Resources\PagesResource;
use Phpsa\FilamentCms\Resources\Resource;

class ListRecords extends FilamentListRecords
{
    protected static string $resource = PagesResource::class;

    protected function getTableQuery(): Builder
    {
        return static::getResource()::getEloquentQuery()->whereNamespace(static::getResource());
    }
}
