<?php

namespace Phpsa\FilamentCms\Resources\BlogPostResource\Pages;

use Phpsa\FilamentCms\Resources\BlogPostResource;
use Phpsa\FilamentCms\Resources\Resource\Pages\ListRecords;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;
}
