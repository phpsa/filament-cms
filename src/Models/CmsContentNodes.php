<?php

namespace Phpsa\FilamentCms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Phpsa\FilamentCms\Models\CmsContentNodes
 *
 * @property int $id
 * @property int $cms_content_pages_id
 * @property string $node
 * @property string|null $content
 * @property-read \Phpsa\FilamentCms\Models\CmsContentPages|null $pages
 * @method static \Illuminate\Database\Eloquent\Builder|CmsContentNodes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CmsContentNodes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CmsContentNodes query()
 * @method static \Illuminate\Database\Eloquent\Builder|CmsContentNodes whereCmsContentPagesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CmsContentNodes whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CmsContentNodes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CmsContentNodes whereNode($value)
 * @mixin \Eloquent
 */
class CmsContentNodes extends Model
{
    use HasFactory;

    protected $touches = ['pages'];

    protected $fillable = [
        'node',
        'content'
    ];

    public $timestamps = false;

    public function pages(): BelongsTo
    {
        return $this->belongsTo(CmsContentPages::class, 'cms_content_pages_id');
    }
}
