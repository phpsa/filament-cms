<?php

namespace Phpsa\FilamentCms\Http\Controllers\Traits;

use MediaEmbed\MediaEmbed;
use Illuminate\Support\Str;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Phpsa\FilamentCms\Models\CmsMedia;

trait HasPageBuilder
{
    protected function processPageBuilder(CmsContentPages $page): CmsContentPages
    {

        $nodes = $page->nodes;

        $pageBuilderNode = blank($this->pageBuilderNode) ? $nodes : $nodes[$this->pageBuilderNode];

        foreach ($pageBuilderNode as &$node) {
            if (blank($node['type']) ?? null) {
                continue;
            }
            $method = Str::of("process")->append($node['type'])->append('Node')->camel()->toString();
            $node['data'] = method_exists($this, $method) ? $this->$method($node) : $node['data'];
        }

        if (! blank($this->pageBuilderNode)) {
            $nodes[$this->pageBuilderNode] = $pageBuilderNode;
        } else {
            $nodes = $pageBuilderNode;
        }
        $page->nodes = $nodes;

        return $page;
    }

    protected function processHeroNode($node)
    {

        return collect($node['data'])
            ->when(
                $node['data']['is_video'],
                fn($vals) => $vals->put('video', [
                    'url'    => $vals['video'],
                    'player' => resolve(MediaEmbed::class)->parseUrl($vals['video'])?->setWidth('100%')?->getEmbedCode()
                ]),
                fn($vals)=> $vals->put('image', CmsMedia::find($vals['image'])?->toArray())
            )
            ->toArray();
    }

    protected function processImageNode($node)
    {
        return  CmsMedia::find($node)?->toArray();
    }
}
