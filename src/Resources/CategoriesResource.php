<?php

namespace Phpsa\FilamentCms\Resources;

use Phpsa\FilamentCms\Resources\Resource;
use Phpsa\FilamentCms\Resources\CategoriesResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class CategoriesResource extends Resource
{
    public static function customFields(): array
    {
        return [
            static::formFieldEditor('nodes.content')
                ->label(strval(__('filament-cms::filament-cms.page.field.content'))),
            SpatieMediaLibraryFileUpload::make('category_image')->directory('blog')
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePages::route('create'),
            'edit'   => Pages\EditPages::route('{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return strval(__('filament-cms::filament-cms.section.category'));
    }

    public static function getPluralLabel(): string
    {
        return strval(__('filament-cms::filament-cms.section.categories'));
    }
}
