<?php

namespace Phpsa\FilamentCms\Resources;

use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Phpsa\FilamentCms\Resources\Resource;
use Filament\Forms\Components\DateTimePicker;
use Phpsa\FilamentCms\Components\MediaPicker;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Filament\Forms\Components\SpatieTagsInput;
use Phpsa\FilamentCms\Components\FeaturedImage;
use Phpsa\FilamentCms\Components\VideoEmbed;
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
            static::formPageBuilder('nodes.content')
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

            Select::make('nodes.category_id')
                ->fromCmsResource(CategoriesResource::class)
                ->label(strval(__('filament-cms::filament-cms.form.field.category')))
                ->searchable()
                ->createOptionForm([
                    TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                    ->afterStateUpdated(function (\Closure $set, ?string $state): void {

                        $set('slug', str($state)->slug());
                    })
                   ,
                    TextInput::make('slug')
                    ->required()
                    ->unique(callback: fn($rule) => $rule->where('namespace', CategoriesResource::class))
                    ->maxLength(255)
                    ->label(strval(__('filament-cms::filament-cms.form.field.slug'))),
                    Hidden::make('user_id')->default(Filament::auth()->user()?->id),
                    Hidden::make('namespace')->default(CategoriesResource::class),
                    Hidden::make('status')->default(config('filament-cms.statusEnum')::default())
                ])
                ->createOptionUsing(
                    fn(array $data) => CmsContentPages::create($data)->getKey()
                )
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
                FileUpload::make('node.gallery')
                    ->label('Gallery')
                    ->multiple()
                        ->enableReordering()
                        ->panelLayout('grid')
            ])
        ];
    }


    public static function customSidebarCards(): array
    {
        return [
            FeaturedImage::make('featured_image'),
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
