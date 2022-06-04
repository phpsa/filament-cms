<?php

namespace Phpsa\FilamentCms\Components\Fields;

use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Phpsa\FilamentCms\Models\CmsContentPages;

class CmsResource
{
    public static function make(string $field): Select
    {
        return Select::make($field)
                ->createOptionUsing(
                    fn(array $data) => CmsContentPages::create($data)->getKey()
                );
    }

    public static function createOptionForm(string $resource): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->reactive()
                ->afterStateUpdated(function (\Closure $set, ?string $state): void {
                    $set('slug', str($state)->slug());
                }),
            TextInput::make('slug')
                ->required()
                ->unique(callback: fn($rule) => $rule->where('namespace', $resource))
                ->maxLength(255)
                ->label(strval(__('filament-cms::filament-cms.form.field.slug'))),
            Hidden::make('user_id')->default(Filament::auth()->user()?->id),
            Hidden::make('namespace')->default($resource),
            Hidden::make('status')->default(config('filament-cms.statusEnum')::default())
        ];
    }
}
