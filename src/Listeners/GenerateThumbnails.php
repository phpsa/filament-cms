<?php

namespace Phpsa\FilamentCms\Listeners;

use Illuminate\Support\Str;
use League\Glide\ServerFactory;
use Phpsa\FilamentCms\Models\CmsMedia;
use Illuminate\Support\Facades\Storage;
use Phpsa\FilamentCms\Events\FileUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateThumbnails implements ShouldQueue
{
    public function handle(FileUploaded $event)
    {
        $media = $event->getMedia();
        if (Str::contains($media->type, 'image')) {
            $this->generateThumbs($media);
        }
    }


    private function generateThumbs(CmsMedia $media): void
    {

        $cache = Storage::disk('local')->path('cache/cms_media');
        $source = Storage::disk($media->disk);

        $server = ServerFactory::create([
            'source' => $source,
            'cache'  => $cache,
        ]);

        $server->setPresets(config('filament-cms.media.sizes', []));

        foreach (config('filament-cms.media.sizes', []) as $name => $values) {
            $server->makeImage($media->filename, ['p' => $name]);
        }
    }
}
