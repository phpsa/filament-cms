<?php

namespace Phpsa\FilamentCms\Events;

use Illuminate\Queue\SerializesModels;
use Phpsa\FilamentCms\Models\CmsMedia;

class FileUploaded
{
    use SerializesModels;

    public function __construct(public CmsMedia $media)
    {
    }

    public function getMedia(): CmsMedia
    {
        return $this->media;
    }
}
