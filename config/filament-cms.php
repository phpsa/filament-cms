<?php

return [
    'editor' =>  [
        'class' => \Filament\Forms\Components\RichEditor::class,
        'enabledToolbarButtons' => [],
        'disableToolbarButtons' => [],
        'disableAllToolbarButtons' => false
    ],
    'statusEnum' => \Phpsa\FilamentCms\Enum\StatusEnum::class,
    'media_conversions' => [
        'thumb' => fn($conversion) => $conversion->width(360)
                        ->height(360)
                        ->sharpen(10),
        'thumb_cropped' => fn($conversion) => $conversion
                        ->crop('crop-center', 400, 400)
    ],
    'resources' => [
        \Phpsa\FilamentCms\Resources\PagesResource::class,
        \Phpsa\FilamentCms\Resources\CategoriesResource::class,
        \Phpsa\FilamentCms\Resources\BlogPostResource::class,
    ],
    'builders' => [
        'default' => \Phpsa\FilamentCms\Builders\Simple::class,
        'BlogPostResource' =>  \Phpsa\FilamentCms\Builders\BlogPost::class,
    ],
];
