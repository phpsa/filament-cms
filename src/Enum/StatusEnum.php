<?php

namespace Phpsa\FilamentCms\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self publish()
 * @method static self pending()
 * @method static self draft()
 * @method static self password()
 * @method static self role()
 * @method static self auth()
 */
class StatusEnum extends Enum
{
    public static function labels(): array
    {
        return [
            'publish'  => __('filament-cms::filament-cms.enum.status.publish'),
            'pending'  => __('filament-cms::filament-cms.enum.status.pending'),
            'draft'    => __('filament-cms::filament-cms.enum.status.draft'),
            'password' => __('filament-cms::filament-cms.enum.status.password'),
            'role'     => __('filament-cms::filament-cms.enum.status.role'),
            'auth'     => __('filament-cms::filament-cms.enum.status.authed'),
        ];
    }

    public static function colors(): array
    {
        return [
            'publish'  => 'success',
            'pending'  => 'info',
            'draft'    => 'secondary',
            'password' => 'warning',
            'role'     => 'warning',
            'auth'     => 'warning',
        ];
    }

    public function color(): string
    {
        return static::colors()[$this->value];
    }

    public static function toBadgeColors()
    {

        $opts = [];

        foreach (static::colors() as $status => $color) {
            $opts[$color][] = $status;
        }
        $callback = [];
        foreach ($opts as $color => $fields) {
            $callback[$color] = fn($state) => in_array($state, $fields);
        }

        return $callback;
    }

    public static function default(): string
    {
        return 'publish';
    }

    public static function passwordProtected(): ?string
    {
        return 'password';
    }

    public static function roleProtected(): ?string
    {
        return 'role';
    }

    public static function authProtected(): ?string
    {
        return 'auth';
    }
}
