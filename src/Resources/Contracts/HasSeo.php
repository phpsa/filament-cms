<?php

namespace Phpsa\FilamentCms\Resources\Contracts;

use Illuminate\Support\Str;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/**
 * @property bool $hasSeo
 */
trait HasSeo
{
    public static function makeSEO(array $except = []): ?Group
    {
        if (static::$hasSeo === false) {
            return null;
        }

        return Group::make()->relationship('seo')->schema(
            collect([
                'title'             => TextInput::make('title')
                ->label(__('Meta Title')),
                'author'            => TextInput::make('author')
                ->label(__('Meta User')),
                'description'       => Select::make('description')->live()->options(
                    [
                        'inherit' => __('Inhertit'),
                        'field'   => __('From Field'),
                        'custom'  => __('Custom'),
                    ]
                )->default('inherit'),
                'description_field' => Textarea::make('description_field')
                ->helperText(fn (?string $state): string => (string) Str::of(strlen($state))
                        ->append(' / ')
                        ->append(160 . ' ')
                        ->append('maximum length')->lower())
                        ->live()
                        ->visible(fn($get) => $get('description') !== 'inherit'),
                'robots'            => Select::make('robots')
                ->columnStart(1)
                ->live()->options(
                    [
                        'inherit'  => __('Inhertit'),
                        'custom'   => __('Custom'),
                        'disabled' => __('Disabled'),
                    ]
                )->default('inherit'),
                'robots'            => Select::make('robots_custom')
                        ->multiple()
                        ->live()->options(
                            [
                                'noindex'  => 'noindex',
                                'nofollow' => 'nofollow',
                            ]
                        )->default('inherit')
                        ->visible(fn($get) => $get('robots') === 'custom'),
                'social_image'      => TextInput::make('social_image')
                        ->label(__('Social Image Field'))->helperText(__('Leave blank to use the default')),
                'twitter_handle'    => TextInput::make('twitter_handle')
                        ->label(__('Twitter Handle'))->helperText(__('Leave blank to use the default')),
            ])->filter()->except($except)->toArray()
        );
    }
}
