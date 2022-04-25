<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Illuminate\Routing\Controller;
use Phpsa\FilamentCms\Resources\BlogPostResource;

class CmsBlogPostController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, CmsContentPages $page)
    {
        abort_unless($page->namespace === BlogPostResource::class, 404);

        $topic = $page->relatedNode('category_id');

        return View::first(['cms.category.post','filament-cms::cms.category.post'])
            ->with('post', $page)
            ->with('topic', $topic);
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Http\Request $request
     * @param string $topic
     * @param \Phpsa\FilamentCms\Models\CmsContentPages $page
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View
     */
    public function showWithTopic(Request $request, string $topic, CmsContentPages $page)
    {
        return $this->show($request, $page);
    }
}
