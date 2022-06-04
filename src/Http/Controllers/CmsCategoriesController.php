<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Illuminate\Routing\Controller;
use Phpsa\FilamentCms\Http\Controllers\Traits\HasCmsData;
use Phpsa\FilamentCms\Resources\BlogPostResource;
use Phpsa\FilamentCms\Resources\CategoriesResource;

class CmsCategoriesController extends Controller
{
    use HasCmsData;

    /**
     * @var string
     */
    protected string $view = 'cms.category.show';

    /**
     * @var class-string
     */
    protected string $resource = CategoriesResource::class;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View
     */
    protected function viewData(CmsContentPages $page): array
    {
        return [
            'category' => $page,
            'posts'    => CmsContentPages::withRelated(
                'category_id',
                $page->id,
                BlogPostResource::class
            )->simplePaginate(8)
        ];
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

        return request()->wantsJson() ? response()->json($categories)
        : View::first(
            ['cms.category.index','filament-cms::cms.category.index'],
            ['categories' => $categories]
        );
    }
}
