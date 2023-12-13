<?php

namespace Phpsa\FilamentCms\Resources\Contracts;

use Filament\Forms\Components\TextInput;

/**
 * @property bool $hasSlug
 */
trait HasSlug
{
    public static function formFieldSlug(): ?TextInput
    {

        return static::$hasSlug
        ? TextInput::make('slug')
            ->required()
            ->unique(ignoreRecord: true, modifyRuleUsing: fn($rule) => $rule->where('namespace', get_called_class()))
            ->maxLength(255)
            ->label(strval(__('filament-cms::filament-cms.form.field.slug')))
        : null;
    }
}
