<?php

namespace Phpsa\FilamentCms\Resources;

use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Phpsa\FilamentCms\Resources\Resource;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\SpatieTagsInput;
use Phpsa\FilamentCms\Components\Fields\CmsResource;
use Phpsa\FilamentCms\Components\Sections\FeaturedImageSection;
use Phpsa\FilamentCms\Components\Fields\Gallery;
use Phpsa\FilamentCms\Components\Fields\VideoEmbed;
use Phpsa\FilamentCms\Resources\BlogPostResource\Pages;

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
            Textarea::make('nodes.excerpt')
                            ->rows(2)
                            ->minLength(50)
                            ->maxLength(1000)
                            ->columnSpan(2),
            MarkdownEditor::make('nodes.content')->columnSpan(2)
                ->label(strval(__('filament-cms::filament-cms.page.field.content'))),

            VideoEmbed::make('nodes.video'),

        ];
    }

    public static function customMeta(): array
    {
        return [
            DateTimePicker::make('published_at')
                            ->label(strval(__('filament-cms::filament-cms.form.field.publish.date')))
                            ->default(now()),
            CmsResource::make('nodes.category_id')
                ->fromCmsResource(CategoriesResource::class)
                ->createOptionForm(CmsResource::createOptionForm(CategoriesResource::class))
                ->label(strval(__('filament-cms::filament-cms.form.field.category')))
                ->searchable()
                ->required(),

            SpatieTagsInput::make('tags')
            ->type('blogTags')
            ->label(strval(__('filament-cms::filament-cms.form.field.tags')))
                            ->required(),
        ];
    }

    public static function customTabs(): array
    {
        return [
            Tab::make(strval(__('filament-cms::filament-cms.form.section.blog.gallery')))
            ->schema([
                Gallery::make('node.gallery')->columnSpan(1)
            ])->columns(3)
        ];
    }


    public static function sidebarCards(): array
    {
        return [
            FeaturedImageSection::make('featured_image'),
        ];
    }

    public static function tablePublishedColumn(): array
    {

        return array_merge(
            parent::tablePublishedColumn(),
            [
                TextColumn::make('published_at')
                ->label(strval(__('filament-cms::filament-cms.table.column.published')))
                ->dateTime()
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
