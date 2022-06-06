<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use MediaEmbed\MediaEmbed;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Phpsa\FilamentCms\Resources\PagesResource;
use Phpsa\FilamentCms\Http\Controllers\Traits\HasCmsData;
use Phpsa\FilamentCms\Http\Controllers\Traits\HasPageBuilder;
use Phpsa\FilamentCms\Models\CmsMedia;

class CmsPageController extends Controller
{
    use HasCmsData;
    use HasPageBuilder;

    protected string $pageBuilderNode = 'content';

    /**
     * @var string
     */
    protected string $view = 'cms.page';

    /**
     * @var class-string
     */
    protected string $resource = PagesResource::class;
}
