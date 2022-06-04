<?php

namespace Phpsa\FilamentCms\Components\Fields;

use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;

class CmsStatus
{
    public static function make()
    {
        /** @var \Phpsa\FilamentCms\Enum\StatusEnum $status */
        $status = config('filament-cms.statusEnum');

        $isPasswordProtected = $status::passwordProtected();
        $isRoleProtected = $status::roleProtected();

        return collect([
            Select::make('status')
                ->label(strval(__('filament-cms::filament-cms.form.field.status')))
                ->default($status::default())
                ->required()
                ->reactive()
                ->options($status::toArray())
        ])
        ->when($isPasswordProtected, fn($collection) => $collection->push(static::getPasswordField($isPasswordProtected)))
        ->when($isRoleProtected, fn($collection) => $collection->push(static::getRoleSelect($isRoleProtected)))
        ->toArray();
    }

    protected static function getPasswordField(string $statusString): Field
    {
        return TextInput::make('security.password')
                    ->label(strval(__('filament-cms::filament-cms.form.field.password')))
                    ->reactive()
                    ->required()
                    ->visible(
                        fn (\Closure $get): bool => $get('status') === $statusString
                    );
    }

    protected static function getRoleSelect(string $statusString): Field
    {
        return MultiSelect::make('security.roles')
                    ->label(strval(__('filament-cms::filament-cms.form.field.roles')))
                    ->reactive()
                    ->options(fn() => Role::all()->mapWithKeys(fn($role) => [$role->name => $role->name])->toArray())
                    ->required()
                    ->visible(
                        fn (\Closure $get): bool => $get('status') === $statusString
                    );
    }
}
