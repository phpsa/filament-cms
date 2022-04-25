<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Illuminate\Routing\Controller;
use Phpsa\FilamentCms\Resources\PagesResource;

class CmsPageController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View
     */
    public function __invoke(Request $request, CmsContentPages $page)
    {
        abort_unless($page->namespace === PagesResource::class, 404);

        return View::first(['cms.page','filament-cms::cms.page'])
            ->with('page', $page);
    }
}
