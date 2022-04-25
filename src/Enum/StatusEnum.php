<?php

namespace Phpsa\FilamentCms\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self publish()
 * @method static self draft()
 * @method static self private()
 * @method static self pending()
 * @method static self inherit()
 */
class StatusEnum extends Enum
{
    public static function labels(): array
    {
        return [
            'publish' => __('filament-cms::filament-cms.enum.status.publish'),
            'draft'   => __('filament-cms::filament-cms.enum.status.draft'),
            'private' => __('filament-cms::filament-cms.enum.status.private'),
            'pending' => __('filament-cms::filament-cms.enum.status.pending'),
            'inherit' => __('filament-cms::filament-cms.enum.status.inherit'),
        ];
    }

    public static function colors(): array
    {
        return [
            'publish' => 'success',
            'draft'   => 'secondary',
            'private' => 'danger',
            'pending' => 'info',
            'inherit' => 'primary',
        ];
    }

    public function color(): string
    {
        return static::colors()[$this->value];
    }

    public static function default(): string
    {
        return 'publish';
    }

    public static function passwordProtected(): ?string
    {
        return 'private';
    }
}
