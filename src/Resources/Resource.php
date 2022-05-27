<?php

namespace Phpsa\FilamentCms\Resources;

use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use RalphJSmit\Filament\SEO\SEO;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Filament\Resources\Resource as FilamentResource;
use Phpsa\FilamentCms\Resources\Contracts\IsCmsResource;

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
                    SelectFilter::make('with_trashed')->options(
                        [
                            'withoutTrashed' => __('filament-cms::filament-cms.table.filter.active'),
                            'onlyTrashed'    => __('filament-cms::filament-cms.table.filter.deleted'),
                        ]
                    )
                ->query(
                    fn($builder, $filter) => match ($filter['value']) {
                        'withoutTrashed' => $builder->withoutTrashed(),
                    'onlyTrashed'    => $builder->onlyTrashed(),
                    default    => $builder->withTrashed(),
                    }
                )->default('withoutTrashed'),
                    Filter::make('created_at')->form([
                        DatePicker::make('created_from')
                        ->label(strval(__('filament-cms::filament-cms.table.filter.created_from')))
                        ->minDate('2020-01-01')
                ->maxDate(now()),
                        DatePicker::make('created_until')
                        ->label(strval(__('filament-cms::filament-cms.table.filter.created_until')))
                        ->minDate('2020-01-01')
                        ->maxDate(now())
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                    }),

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
            ->schema([
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
                        )->columnSpan(3),
                    ]
                )->columnSpan(3),
                Grid::make()->schema(
                    static::generateFormSidebar()
                )->columnSpan(1),
            ]);
    }

    public static function generateSingleLayout(Form $form): Form
    {
        return $form->columns(4)
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
