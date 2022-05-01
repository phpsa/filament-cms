<?php

namespace Phpsa\FilamentCms\Resources\BlogPostResource\Pages;

use Phpsa\FilamentCms\Resources\BlogPostResource;
use Phpsa\FilamentCms\Resources\Resource\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected static array $dateColumns = [
        'published_at'
    ];
}
