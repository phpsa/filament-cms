<?php

namespace Phpsa\FilamentCms\Components;

use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\BaseFileUpload;

class MediaUpload extends FileUpload
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setDefaultsFromConfig();

        $this->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file, $state, $set): array {

            $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';
            $filename = $component->getUploadedFileNameForStorage($file);

            $width = $height = null;

            if (Str::contains($file->getMimeType(), 'image')) {
                $image = Image::make($file->getRealPath());
                $width = $image->getWidth();
                $height = $image->getHeight();
            }

            $fileExtension = $file->getClientOriginalExtension();
            $filePath = $component->getDirectory();
            $disk = $component->getDiskName();

            $filename = Str::of($filename)->whenEndsWith("." . $fileExtension, fn($str) => $str, fn($str) => $str->append("." . $fileExtension))->toString();

            if (Storage::disk($disk)->exists(ltrim($filePath . '/' . $filename, '/'))) {
                $filename = $filename . '-' . time();
            }

            return [
                'ext'       => $fileExtension,
                'type'      => $file->getMimeType(),
                'width'     => $width,
                'height'    => $height,
                'disk'      => $disk,
                'directory' => $filePath,
                'size'      => $file->getSize(),
                'filename'  => $file->{$storeMethod}($filePath, $filename, $disk),
            ];
        });
    }

    protected function setDefaultsFromConfig(): void
    {
        $this->preserveFilenames(config('filament-cms.media.preserve_file_names', true))
                ->disableLabel()
                ->maxWidth(config('filament-cms.media.max_width', 2500))
                ->acceptedFileTypes(config('filament-cms.media.accept_mime'))
                ->directory(config('filament-cms.media.directory', 'media'))
                ->disk(config('filament.default_filesystem_disk', 'public'))
                ->rules(config('filament-cms.media.rules'))
                ->required()
                ->maxFiles(1)
                ->minSize(config('filament-cms.media.min_size'))
                ->maxSize(config('filament-cms.media.max_size'))
                ->panelAspectRatio('16:9');
    }

    public function saveUploadedFiles(): void
    {
        if (blank($this->getState())) {
            $this->state([]);

            return;
        }

        if (! is_array($this->getState())) {
            $this->state([$this->getState()]);
        }

        $state = array_map(function (TemporaryUploadedFile | array $file) {
            if (! $file instanceof TemporaryUploadedFile) {
                return $file;
            }

            $callback = $this->saveUploadedFileUsing;

            if (! $callback) {
                $file->delete();

                return $file;
            }

            $storedFile = $this->evaluate($callback, [
                'file' => $file,
            ]);

            $file->delete();

            return $storedFile;
        }, $this->getState());

        if ($this->canReorder && ($callback = $this->reorderUploadedFilesUsing)) {
            $state = $this->evaluate($callback, [
                'state' => $state,
            ]);
        }

        $this->state($state);
    }
}
