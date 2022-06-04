<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Phpsa\FilamentCms\Resources\BlogPostResource;
use Phpsa\FilamentCms\Http\Controllers\Traits\HasCmsData;

class CmsBlogPostController extends Controller
{
    use HasCmsData;

    /**
     * @var string
     */
    protected string $view = 'cms.category.post';

    /**
     * @var class-string
     */
    protected string $resource = BlogPostResource::class;

      /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View
     */
    protected function viewData(CmsContentPages $page): array
    {
        return [
            'page'  => $page,
            'topic' =>  $page->relatedNode('category_id')
        ];
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
    public function showWithTopic(Request $request, string $topic, string $page)
    {
        return $this->show($request, $page);
    }
}
