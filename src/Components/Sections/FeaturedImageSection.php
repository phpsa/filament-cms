<?php

namespace Phpsa\FilamentCms\Components\Sections;

use Filament\Forms\Components\Section;

class FeaturedImageSection
{
    public static function make(string $field, ?string $sectionLabel = null, bool|\Closure $collapsed = true): Section
    {
        $class = config('filament-cms.uploader.class');
        return Section::make($sectionLabel ?? strval(__('filament-cms::filament-cms.form.section.blog.featured')))
            ->schema([
                $class::make($field)->disableLabel(true),
            ])->collapsible()->collapsed($collapsed);
    }
}
