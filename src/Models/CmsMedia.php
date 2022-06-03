<?php

namespace Phpsa\FilamentCms\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Spatie\Tags\HasTags;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Phpsa\FilamentCms\Events\FileUploaded;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsMedia extends Model
{
    use HasFactory;
    use HasTags;

    protected $fillable = [
        'filename',
        'ext',
        'type',
        'alt',
        'title',
        'description',
        'caption',
        'width',
        'height',
        'disk',
        'directory',
        'size',
    ];

    protected $casts = [
        'width'  => 'integer',
        'height' => 'integer',
    ];

    protected $appends = [
        'url',
        'thumbnail_url',
        'medium_url',
        'large_url'
    ];

    protected static function booted()
    {
        static::creating(function (CmsMedia $media) {
            foreach ($media->filename as $k => $v) {
                $media->{$k} = $v;
            }
            $media->uuid = Str::uuid();
        });

        static::created(function (CmsMedia $media) {
            $media->refresh();
            event(new FileUploaded($media));
        });

        static::deleted(function (CmsMedia $media) {
            $pathinfo = pathinfo($media->filename);
            foreach (config('filament-cms.media.presets') as $name => $data) {
                Storage::disk($media->disk)->delete($pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $name . '.' . $media->ext);
            }
            Storage::disk($media->disk)->delete($media->filename);
        });
    }

    public function url(): Attribute
    {
        return Attribute::get(fn() => Storage::disk($this->disk)->url($this->filename));
    }

    public function temporaryUrl(): Attribute
    {
        return Attribute::get(fn(): string => $this->getTemporaryUrlForFile());
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn() => $this->getTemporaryUrlForFile(options: ['p' => 'thumb']));
    }

    public function mediumUrl(): Attribute
    {
        return Attribute::get(fn() => $this->getTemporaryUrlForFile(options: ['p' => 'thumb_medium']));
    }

    public function largeUrl(): Attribute
    {
        return Attribute::get(fn() => $this->getTemporaryUrlForFile(options: ['p' => 'thumb_large']));
    }

    public function getTemporaryUrlForFile(?\DateTimeInterface $expires = null, array $options = []): string
    {
        $expires ?? now()->addMinutes(15);
        $driver = config('filesystems.disks.' . $this->disk . '.driver');
        $disk = Storage::disk($this->disk);
        if ($driver === 'local') {
            $disk->buildTemporaryUrlsUsing(fn($path, $expire, $options = [])=> URL::temporarySignedRoute(
                'filament-cms.cms-media.glide',
                $expire,
                array_merge($options, ['media' =>  $path])
            ));
        }

        return $disk->temporaryUrl($this->uuid, now()->addMinutes(5), $options);
    }

    public function scopeFiltered(Builder $builder, string $search): Builder
    {
        return $builder->where(
            fn(Builder $q): Builder => $q->where('filename', 'like', '%' . $search . '%')
                ->orWhere('alt', 'like', '%' . $search . '%')
                ->orWhere('caption', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
        );
    }

    public function humanSize(int $precision = 2): string
    {
        $i = floor(log($this->size ?: 1, 1024));
        return round($this->size / pow(1024, $i), [0,0,2,2,3][$i], $precision) . ['B','kB','MB','GB','TB'][$i];
    }
}
