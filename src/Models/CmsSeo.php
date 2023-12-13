<?php

namespace Phpsa\FilamentCms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsContentPages extends Model
{
    protected $fillable = [
        'cms_content_pages_id',
        'title',
        'author',
        'description',
        'description_custom',
        'robots',
        'robots_custom',
        'social_image',
        'twitter_handle',
    ];

    public function cmsContentPages(): BelongsTo
    {
        return $this->belongsTo(CmsContentPages::class);
    }
}
