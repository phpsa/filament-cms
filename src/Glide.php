<?php

namespace Phpsa\FilamentCms;

use League\Glide\Server;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use League\Glide\ServerFactory;
use Phpsa\FilamentCms\Models\CmsMedia;
use Illuminate\Support\Facades\Storage;
use League\Glide\Responses\PsrResponseFactory;

class Glide
{
    public static function getServerForMedia(CmsMedia $media): Server
    {

        $cache = storage_path('app/cache/cms_media');
        $source = Storage::disk($media->disk)->getDriver();

        $server = ServerFactory::create([
            'source'   => $source,
            'cache'    => $cache,
            'driver'   => config('filament-cms.media.driver', 'gd'),
            'response' => new GlideResponseFactory(),
            'presets'  => static::fetchPresets($media),
            'defaults' => config('filament-cms.media.defaults', []),
        ]);

        return $server;
    }

    public static function generateThumbs(CmsMedia $media): void
    {
        $server = static::getServerForMedia($media);
        foreach (config('filament-cms.media.presets', []) as $name => $values) {
            $server->makeImage($media->filename, ['p' => $name]);
        }
    }


    public static function output(CmsMedia $media, array $params = []): void
    {
        static::getServerForMedia($media)->outputImage($media->filename, $params);
    }

    public static function fetchPresets(CmsMedia $media): array
    {
        return collect(
            config('filament-cms.media.presets', [])
        )
        ->map(
            function ($preset) use ($media) {
                if (Arr::has($preset, 'fit') && Arr::get($preset, 'fit') === 'crop') {
                    $preset['fit'] = Str::of($media->focal_point ?? config('filament-cms.media.focal'))
                        ->replace("%", "")
                        ->prepend('crop-')
                        ->slug()
                        ->toString();
                }
                return $preset;
            }
        )
        ->toArray();
    }
}
