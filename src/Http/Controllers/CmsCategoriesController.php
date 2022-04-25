<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Illuminate\Routing\Controller;
use Phpsa\FilamentCms\Resources\BlogPostResource;
use Phpsa\FilamentCms\Resources\CategoriesResource;

class CmsCategoriesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, CmsContentPages $page)
    {
        abort_unless($page->namespace === CategoriesResource::class, 404);

        $posts = CmsContentPages::withRelated('category_id', $page->id, BlogPostResource::class)->simplePaginate(8);

        return View::first(['cms.category.show','filament-cms::cms.category.show'])
            ->with('category', $page)
            ->with('posts', $posts);
    }

     /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $categories = CmsContentPages::whereNamespace(CategoriesResource::class)->simplePaginate(8);

        return View::first(['cms.category.index','filament-cms::cms.category.index'])
            ->with('categories', $categories);
    }
}
