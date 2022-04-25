<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages\Contract;

use Phpsa\FilamentCms\Models\CmsContentPages;

trait HasNode
{
    /**
     * Undocumented function
     *
     * @param array<string, mixed> $data
     * @param \Phpsa\FilamentCms\Models\CmsContentPages $record
     *
     * @return void
     */
    protected function saveNodes(array $data, CmsContentPages $record): void
    {
        collect($data)
        ->map(fn($value) => is_array($value) || is_object($value) ? json_encode($value) : $value)
        ->each(
            function ($content, $key) use ($record) {
                $node = $record->nodes()->whereNode($key)->firstOrNew();
                $node->fill(
                    [
                        'node'    => $key,
                        'content' => $content
                    ]
                );
                $node->save();
            }
        );
    }
}
