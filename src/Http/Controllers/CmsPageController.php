<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Phpsa\FilamentCms\Resources\PagesResource;
use Phpsa\FilamentCms\Http\Controllers\Traits\HasCmsData;

class CmsPageController extends Controller
{
    use HasCmsData;

    /**
     * @var string
     */
    protected string $view = 'cms.page';

    /**
     * @var class-string
     */
    protected string $resource = PagesResource::class;
}
