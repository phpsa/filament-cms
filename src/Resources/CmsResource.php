<?php

namespace Phpsa\FilamentCms\Resources;

use Exception;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\DeleteBulkAction;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Filament\Tables\Actions\RestoreBulkAction;
use Phpsa\FilamentCms\Components\Forms\Sidebar;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\Container\BindingResolutionException;
use Phpsa\FilamentCms\Resources\CmsResource\Pages\EditCmsPage;
use Phpsa\FilamentCms\Resources\CmsResource\Pages\ListCmsPage;
use Phpsa\FilamentCms\Resources\CmsResource\Pages\CreateCmsPage;
use Phpsa\FilamentCms\Resources\Contracts\HasSlug;

class CmsResource extends Resource
{
    use HasSlug;

    protected static ?string $model = CmsContentPages::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $hasSlug = true;

    protected static bool $hasParent = true;

    protected static bool $hasSEO = true;


    public static function getNavigationGroup(): ?string
    {
        return (string) __('filament-cms::filament-cms.section.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([Sidebar::make(
                collect(static::mainFormArea())->filter()->toArray(),
                collect(static::sidebarFormArea())->filter()->toArray(),
            )
            ]);
    }

    public static function mainFormArea(): array
    {
        return [
            Section::make()->schema(collect([
                ...static::titleArea(),
                ...static::formFields()
            ])->filter()->toArray())->columns(2)
        ];
    }

    public static function titleArea(): array
    {
        return [TextInput::make('name')
        ->label(strval(__('filament-cms::filament-cms.form.field.name')))
        ->required(),
            static::formFieldSlug(),
        ];
    }

    public static function formFields(): array
    {
        return [];
    }

    public static function sidebarFormArea(): array
    {
        return [
            Section::make()->schema(collect([
                static::formFieldParent(),
            ])->filter()->toArray())
        ];
    }


    public static function formFieldParent(): ?Select
    {
        return static::$hasParent
        ?

        Select::make('parent_id')
            ->options(fn($record) => static::getModel()::where('id', '!=', $record?->id)->whereNamespace(
                get_called_class()
            )
            ->pluck('name', 'id'))
            ->label(strval(__('filament-cms::filament-cms.form.field.parent')))
        : null;
    }

    /*
     * @param Table $table
     * @return Table
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws BindingResolutionException
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::tableColumns())
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]);
    }

    protected static tableColumns(): array
    {
    return [
        'id'          => TextColumn::make('id')
                ->label(strval(__('filament-cms::filament-cms.table.column.id')))
                ->sortable(),
        'name'        => TextColumn::make('name')
                ->label(strval(__('filament-cms::filament-cms.table.column.name')))
                ->searchable()
                ->when(
                    static::$hasSlug,
                    fn($column) => $column->description(fn($record) => $record->slug)
                )
                ->sortable(),
        'parent_name' => TextColumn::make('parent.name')->visible(self::$hasParent),
        'created_at'  => TextColumn::make('created_at')
                ->label(strval(__('filament-cms::filament-cms.table.column.created')))
                ->dateTime()
                ->sortable(),
    ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCmsPage::route('/'),
            'create' => CreateCmsPage::route('/create'),
            'edit'   => EditCmsPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? (string) Str::of(class_basename(get_called_class()))
            ->replaceLast('Resource', '')
            ->plural()
            ->headline()
            ->toString();
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? (string) Str::of(class_basename(static::getNavigationLabel()))
            ->singular()
            ->title()
            ->toString();
    }
    public static function getSlug(): string
    {
        return static::$slug ?? (string) Str::of(static::getNavigationLabel())
            ->prepend('cms-')
            ->plural()
            ->kebab()
            ->slug();
    }
}
