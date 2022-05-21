<?php

namespace Phpsa\FilamentCms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class FeaturedImage
{
    public static function make(string $field, ?string $sectionLabel = null): Section
    {
        return Section::make($sectionLabel ?? strval(__('filament-cms::filament-cms.form.section.blog.featured')))
            ->schema([
                SpatieMediaLibraryFileUpload::make($field)->disableLabel(true),
            ])->collapsible()->collapsed();
    }
}
