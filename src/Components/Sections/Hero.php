<?php

namespace Phpsa\FilamentCms\Components\Sections;

use Closure;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Phpsa\FilamentCms\Components\Fields\VideoEmbed;
use Phpsa\FilamentCms\Components\Fields\MediaPicker;

class Hero
{
    public static function make(string $field, ?string $label = null): Section
    {
        return Section::make($label ?? $field)
            ->schema([
                Toggle::make($field . '.is_video')
                    ->label('Is Video')
                    ->reactive(),
                MediaPicker::make($field . '.image')
                    ->label('Image')
                    ->hidden(fn (Closure $get): bool => $get($field . '.is_video')),
                VideoEmbed::make($field . '.video')
                    ->label('Video Url')
                    ->visible(fn (Closure $get): bool => $get($field . '.is_video'))

                    ->reactive(),
                Textarea::make($field . '.cta')
                    ->label('Call to Action')
                    ->rows(3),
            ]);
    }
}
