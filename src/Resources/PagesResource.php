<?php

namespace Phpsa\FilamentCms\Resources;

use Phpsa\FilamentCms\Resources\Resource;
use Phpsa\FilamentCms\Resources\PagesResource\Pages;

class PagesResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';


    public static function customCards(): array
    {
        return [
            static::formPageBuilder('nodes.content')
                ->label(strval(__('filament-cms::filament-cms.page.field.content'))),
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
        return strval(__('filament-cms::filament-cms.section.page'));
    }

    public static function getPluralLabel(): string
    {
        return strval(__('filament-cms::filament-cms.section.pages'));
    }
}
