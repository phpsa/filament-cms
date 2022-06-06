<?php

namespace Phpsa\FilamentCms\Components\Blocks;

use Closure;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Builder\Block;
use Phpsa\FilamentCms\Components\Fields\VideoEmbed;
use Phpsa\FilamentCms\Components\Fields\MediaPicker;

class HeroBlock
{
    public static function make(string $label): Block
    {
        return Block::make('Hero')->label($label)
            ->schema([
                Toggle::make('is_video')
                    ->label('Is Video')
                    ->reactive(),
                MediaPicker::make('image')
                    ->label('Image')
                    ->hidden(fn (Closure $get): bool => $get('is_video')),
                VideoEmbed::make('video')
                    ->label('Video Url')
                    ->visible(fn (Closure $get): bool => $get('is_video'))

                    ->reactive(),
                Textarea::make('cta')
                    ->label('Call to Action')
                    ->rows(3),
            ]);
    }
}
