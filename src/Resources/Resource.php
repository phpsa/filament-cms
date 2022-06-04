<?php

namespace Phpsa\FilamentCms\Resources;

use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use RalphJSmit\Filament\SEO\SEO;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Columns\TextColumn;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Filament\Resources\Resource as FilamentResource;
use Phpsa\FilamentCms\Components\Filters\CreationFilter;
use Phpsa\FilamentCms\Resources\Contracts\IsCmsResource;
use Phpsa\FilamentCms\Components\Filters\SoftDeleteFilter;

class Resource extends FilamentResource
{
    use IsCmsResource;

    protected static ?string $model = CmsContentPages::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';


    protected static function getNavigationGroup(): ?string
    {
        return strval(__('filament-cms::filament-cms.section.group'));
    }

    public static function table(Table $table): Table
    {
        return $table->columns(
            static::mergeSections(
                [
                    TextColumn::make('id')
                    ->label(strval(__('filament-cms::filament-cms.table.column.id')))
                    ->sortable(),
                    TextColumn::make('name')
                    ->label(strval(__('filament-cms::filament-cms.table.column.name')))
                    ->searchable()
                    ->sortable(),
                ],
                static::customTableColumns(),
                static::tablePublishedColumn(),
                [
                    TextColumn::make('created_at')
                    ->label(strval(__('filament-cms::filament-cms.table.column.created')))
                    ->dateTime()
                    ->sortable(),
                    TextColumn::make('updated_at')
                    ->label(strval(__('filament-cms::filament-cms.table.column.updated')))
                    ->dateTime()->sortable(),
                ],
            )
        )->filters(
            static::mergeSections(
                static::filterPublishable(),
                [
                    SoftDeleteFilter::make(),

                    CreationFilter::make(),

                ],
                static::customTableFilters()
            )
        );
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function generateTabbedLayout(Form $form): Form
    {

        return $form
            ->columns(4)
            ->schema(
                collect([
                    Grid::make()->schema(
                        [
                            Tabs::make('Tab Form')
                                ->tabs(
                                    static::mergeSections(
                                        [
                                            Tabs\Tab::make(strval(__('filament-cms::filament-cms.form.section.general')))
                                            ->schema(
                                                static::generateFormGeneralSection()
                                            )
                                        ],
                                        static::customTabs(),
                                        [
                                            static::$hasSeo ? Tabs\Tab::make('SEO')
                                            ->schema([
                                                Card::make()->schema([SEO::make()])
                                            ]) : null
                                        ]
                                    )
                                )
                                 ->columnSpan(static::$disableSidebar ? 4 : 3),
                        ]
                    )->columnSpan(static::$disableSidebar ? 4 : 3),
                    static::$disableSidebar ? null : Grid::make()->schema(
                        static::generateFormSidebar()
                    )->columnSpan(1),

                ])->filter()->toArray()
            );
    }

    public static function generateSingleLayout(Form $form): Form
    {
        return static::$disableSidebar
        ? $form
            ->schema([
                Grid::make()->schema(
                    static::generateFormGeneralSection()
                ),
            ])
        :
        $form->columns(4)
            ->schema([
                Grid::make()->schema(
                    static::generateFormGeneralSection()
                )->columnSpan(3),

                Grid::make()->schema(
                    static::generateFormSidebar()
                )->columnSpan(1),
            ]);
    }

    public static function form(Form $form): Form
    {

        return static::usingTabbedLayout()
        ? static::generateTabbedLayout($form)
        : static::generateSingleLayout($form);
    }

    public static function getSlug(): string
    {
        return static::$slug ?? (string) Str::of(class_basename(get_called_class()))
            ->replaceLast('Resource', '')
            ->prepend('cms-')
            ->plural()
            ->kebab()
            ->slug();
    }

    public static function usingTabbedLayout(): bool
    {
        return static::$tabbedLayout;
    }
}
