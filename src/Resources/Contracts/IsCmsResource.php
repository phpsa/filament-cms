<?php

namespace Phpsa\FilamentCms\Resources\Contracts;

use RalphJSmit\Filament\SEO\SEO;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Section;
use Phpsa\FilamentCms\Builders\Simple;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Phpsa\FilamentCms\Components\Fields\CmsStatus;
use Phpsa\FilamentCms\Components\Fields\RichTextEditor;
use Phpsa\FilamentCms\Components\Filters\PublishedFilter;
use Phpsa\FilamentCms\Resources\Resource\Pages\EditRecord;

trait IsCmsResource
{
    protected static bool $hasSlug = true;

    protected static bool $hasSeo = true;

    protected static bool $hasParent = true;

    protected static bool $hasMeta = true;

    protected static bool $isPublishable = true;

    protected static bool $tabbedLayout = false;

    protected static bool $disableSidebar = false;


    public static function customFields(): array
    {
        return [];
    }

    public static function customMeta(): array
    {
        return [];
    }

    public static function customCards(): array
    {
        return [];
    }

    public static function sidebarCards(): array
    {
        return [];
    }

    public static function customTableColumns(): array
    {
        return [];
    }

    public static function customTableFilters(): array
    {
        return [];
    }

    public static function customTabs(): array
    {
        return [];
    }

    public static function formFieldEditor(string $field, ?string $label = null): Field
    {
        return RichTextEditor::make($field)->label($label);
    }


    public static function formPageBuilder(string $field): Builder
    {
        $type = class_basename(get_called_class());
        $use = config('filament-cms.builders.' . $type, config('filament-cms.builders.default', Simple::class));
        return $use::make($field);
    }

    public static function formFieldName(string $field = 'name'): TextInput
    {
        $field =  TextInput::make($field)
                            ->required()
                            ->maxLength(255);
        return static::$hasSlug
        ? $field
            ->reactive()
            ->afterStateUpdated(function ($livewire, \Closure $set, ?string $state): void {

                if ($livewire instanceof EditRecord) {
                    return;
                }
                $set('slug', str($state)->slug());
            })
        : $field;
    }

    public static function formFieldSlug(): ?TextInput
    {
        return static::$hasSlug
        ? TextInput::make('slug')
            ->required()
            ->unique(ignoreRecord: true, callback: fn($rule) => $rule->where('namespace', get_called_class()))
            ->maxLength(255)
            ->label(strval(__('filament-cms::filament-cms.form.field.slug')))
        : null;
    }

    public static function formFieldParent(): ?Select
    {
        return static::$hasParent
        ?  Select::make('parent_id')
            ->options(fn($record) => static::getModel()::where('id', '!=', $record?->id)->whereNamespace(
                get_called_class()
            )
            ->pluck('name', 'id'))
            ->label(strval(__('filament-cms::filament-cms.form.field.parent')))
        : null;
    }

    protected static function mergeSections(array $default, array ...$extras): array
    {
        return(collect($default))->merge(
            collect($extras)->flatten(1)
        )->filter()->toArray();
    }


    public static function formMetaSection(): ?Section
    {
        return static::$hasMeta
        ? Section::make(strval(__('filament-cms::filament-cms.form.section.meta')))
            ->schema(
                static::mergeSections(
                    [
                        static::formFieldParent()
                    ],
                    static::publishingFields(),
                    static::customMeta()
                )
            )
            ->collapsible()
        : null;
    }

    public static function publishingFields(): array
    {
        return  static::$isPublishable ? CmsStatus::make() : [];
    }

    public static function filterPublishable(): array
    {
        return static::$isPublishable ? [PublishedFilter::make()] : [];
    }

    public static function tablePublishedColumn(): array
    {
        if (static::$isPublishable === false) {
            return [];
        }

        $status = config('filament-cms.statusEnum');

        return [
            BadgeColumn::make('status')
                ->label(strval(__('filament-cms::filament-cms.table.column.status')))
                ->enum($status::toArray())
                ->colors(
                    collect($status::colors())
                        ->mapToGroups(fn($val, $key) => [$val => $key])
                        ->map(fn($val) => fn($state) => $val->contains($state))
                        ->toArray()
                )
                ->sortable()
        ];
    }

    public static function generateFormGeneralSection(): array
    {
        return static::mergeSections(
            [
                Card::make()->columns(2)->schema(
                    static::mergeSections(
                        [
                            static::formFieldName('name')
                                ->label(strval(__('filament-cms::filament-cms.form.field.name'))),
                            static::formFieldSlug()
                        ],
                        static::customFields()
                    )
                ),

            ],
            static::customCards(),
        );
    }

    public static function generateFormSidebar(): array
    {
        return static::mergeSections(
            [
                static::$hasMeta ? Section::make(strval(__('filament-cms::filament-cms.form.section.meta')))
                            ->schema(
                                static::mergeSections(
                                    [
                                        static::formFieldParent()
                                    ],
                                    static::publishingFields(),
                                    static::customMeta()
                                )
                            )
                            ->collapsible() : null,

            ],
            static::sidebarCards(),
            [static::$hasSeo && ! static::$tabbedLayout
                        ? Section::make(strval(__('filament-cms::filament-cms.form.section.seo')))
                            ->schema([
                                SEO::make(),
                            ])
                            ->collapsible()
                            ->collapsed()
                        : null
            ],
        );
    }
}
