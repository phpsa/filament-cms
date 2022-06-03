<?php

use Filament\Forms\Components\FileUpload;
use Phpsa\FilamentCms\Components\MediaPicker;

return [
    'editor' =>  [
        'class' => \Filament\Forms\Components\RichEditor::class,
        'enabledToolbarButtons' => [],
        'disableToolbarButtons' => [],
        'disableAllToolbarButtons' => false
    ],
    'uploader' => [
        'class' => MediaPicker::class
    ],
    'statusEnum' => \Phpsa\FilamentCms\Enum\StatusEnum::class,
    'resources' => [
        \Phpsa\FilamentCms\Resources\PagesResource::class,
        \Phpsa\FilamentCms\Resources\CategoriesResource::class,
        \Phpsa\FilamentCms\Resources\BlogPostResource::class,
        \Phpsa\FilamentCms\Resources\MediaResource::class,
    ],
    'builders' => [
        'default' => \Phpsa\FilamentCms\Builders\Simple::class,
        'BlogPostResource' =>  \Phpsa\FilamentCms\Builders\BlogPost::class,
    ],
    'media' => [
        'driver' => 'gd', //gd or imagick
        //@see https://glide.thephpleague.com/2.0/config/defaults-and-presets/
        'presets' => [
            'thumb' => [
                'w' => 250,
                'h' => 250,
                'fit' => 'crop',
            ],
            'thumb_medium' => [
                'w' => 640,
                'h' => 640,
                'fit' => 'max'
            ],
            'thumb_large' => [
                'w' => 1280,
                'h' => 1280,
                'fit' => 'max'
            ],
            'greyscale' => [
                'filt' => 'greyscale'
            ],
        ],
        'defaults' => [

        ],
        'accept_mime' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/svg+xml',
            'application/pdf'
        ],
        'preserve_filenames' => true,
        'max_width' => 2500,
        'min_size' => 0,
        'max_size' => 3000,
        'directory' => 'media',
        'rules' => []

    ]
];
