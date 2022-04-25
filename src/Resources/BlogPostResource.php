<?php

namespace Phpsa\FilamentCms\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Phpsa\FilamentCms\Resources\Resource;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Phpsa\FilamentCms\Resources\BlogPostResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Phpsa\FilamentCms\Resources\BlogPostResource\RelationManagers;

class BlogPostResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static bool $hasSlug = true;

    protected static bool $hasSeo = true;

    protected static bool $hasParent = false;

    protected static bool $hasMeta = true;

    protected static bool $isPublishable = true;

    protected static bool $tabbedLayout = true;


    public static function customFields(): array
    {
        return [
            Textarea::make('node.excerpt')
                            ->rows(2)
                            ->minLength(50)
                            ->maxLength(1000)
                            ->columnSpan(2),
            static::formFieldEditor('node.content')
                ->label(strval(__('filament-cms::filament-cms.page.field.content'))),

        ];
    }

    public static function customMeta(): array
    {
        return [
            DateTimePicker::make('node.published_at')
                            ->label(strval(__('filament-cms::filament-cms.form.field.publish.date')))
                            ->default(now(static::getUserTimezone())),

            Select::make('node.category_id')
                ->fromCmsResource(CategoriesResource::class)
                ->label(strval(__('filament-cms::filament-cms.form.field.category')))
                ->searchable()
                ->required(),
            SpatieTagsInput::make('tags')
            ->label(strval(__('filament-cms::filament-cms.form.field.tags')))
                            ->required(),
        ];
    }

    public static function customTabs(): array
    {
        return [
            Tab::make(strval(__('filament-cms::filament-cms.form.section.blog.gallery')))
                ->schema([
                    \Filament\Forms\Components\FileUpload::make('gallery_images')
                        ->disableLabel(true)
                        ->directory('blog')
                        ->multiple()
                    //    ->collection('gallery')
                        ->enableReordering()
                        ->panelLayout('grid')
                ])
        ];
    }


    public static function customSidebarCards(): array
    {
        return [
            Section::make(strval(__('filament-cms::filament-cms.form.section.blog.featured')))

                ->schema([
                    SpatieMediaLibraryFileUpload::make('feature_image')->directory('blog')->disableLabel(true),

                ])
        ];
    }

    public static function tablePublishedColumn(): array
    {

        return array_merge(
            parent::tablePublishedColumn(),
            [
                TextColumn::make('nodes.published_at')
                    ->label(strval(__('filament-cms::filament-cms.table.column.published')))
                    ->dateTime(timezone: static::getUserTimezone())
                    ->fromNodeState()
                    ->sortable(),
            ]
        );
    }

    public static function customTableFilters(): array
    {
        return [];
    }


    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit'   => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return strval(__('blog post'));
    }

    public static function getPluralLabel(): string
    {
        return strval(__('blog posts'));
    }
}
