<?php

namespace Phpsa\FilamentCms\Resources;

use Phpsa\FilamentCms\Resources\Resource;
use Phpsa\FilamentCms\Components\FeaturedImage;
use Phpsa\FilamentCms\Resources\CategoriesResource\Pages;

class CategoriesResource extends Resource
{
    public static function customFields(): array
    {
        return [
            static::formPageBuilder('nodes.content')
                ->label(strval(__('filament-cms::filament-cms.page.field.content'))),
        ];
    }

    public static function customSidebarCards(): array
    {
        return [
            FeaturedImage::make('nodes.category_image'),
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
