<?php

namespace Phpsa\FilamentCms\Models;

use Spatie\Tags\HasTags;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Phpsa\FilamentCms\Enum\StatusEnum;

/**
 * Phpsa\FilamentCms\Models\CmsContentPages
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $namespace
 * @property string $name
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $user_id
 * @property string|null $status
 * @property string|null $security
 * @property-read \Illuminate\Database\Eloquent\Collection|CmsContentPages[] $children
 * @property-read int|null $children_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|CmsContentPages[] $parents
 * @property-read int|null $parents_count
 * @property-read \RalphJSmit\Laravel\SEO\Models\SEO|null $seo
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\Tags\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static Builder|CmsContentPages newModelQuery()
 * @method static Builder|CmsContentPages newQuery()
 * @method static \Illuminate\Database\Query\Builder|CmsContentPages onlyTrashed()
 * @method static Builder|CmsContentPages query()
 * @method static Builder|CmsContentPages whereCreatedAt($value)
 * @method static Builder|CmsContentPages whereDeletedAt($value)
 * @method static Builder|CmsContentPages whereId($value)
 * @method static Builder|CmsContentPages whereName($value)
 * @method static Builder|CmsContentPages whereNamespace($value)
 * @method static Builder|CmsContentPages whereParentId($value)
 * @method static Builder|CmsContentPages whereSecurity($value)
 * @method static Builder|CmsContentPages whereSlug($value)
 * @method static Builder|CmsContentPages whereStatus($value)
 * @method static Builder|CmsContentPages whereUpdatedAt($value)
 * @method static Builder|CmsContentPages whereUserId($value)
 * @method static Builder|CmsContentPages withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array $tags, ?string $type = null)
 * @method static Builder|CmsContentPages withAllTagsOfAnyType($tags)
 * @method static Builder|CmsContentPages withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array $tags, ?string $type = null)
 * @method static Builder|CmsContentPages withAnyTagsOfAnyType($tags)
 * @method static Builder|CmsContentPages withRelated(string $key, $value, $type = null)
 * @method static \Illuminate\Database\Query\Builder|CmsContentPages withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CmsContentPages withoutTrashed()
 * @mixin \Eloquent
 */
class CmsContentPages extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use HasSEO;
    use HasTags;
    use InteractsWithMedia;

    protected $fillable = [
        'parent_id',
        'user_id',
        'namespace',
        'name',
        'slug',
        'status',
        'security',
        'nodes',
        'published_at',
        'expired_at',
    ];

    protected $casts = [
        'nodes'        => 'json',
        'published_at' => 'datetime',
        'expired_at'   => 'datetime'
    ];

    public function registerMediaConversions(Media $media = null): void
    {

        /** @var array<string, \Closure> $conversions */
        $conversions = config('filament_cms.media_conversions', []);
        if (blank($conversions)) {
            return;
        }

        collect($conversions)
            ->each(
                fn($callback, $key) => $callback($this->addMediaConversion($key))
            );
    }


    public function parents(): HasMany
    {
        return $this->hasMany(CmsContentPages::class, 'id', 'parent_id')->with('nodes');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CmsContentPages::class, 'parent_id', 'id')->with('nodes');
    }

    /**
     * @inheritDoc
     */
    public function resolveRouteBinding($value, $field = null)
    {
         /** @phpstan-ignore-next-line */
        return $this->resolveRouteBindingQuery($this, $value, $field)
            ->with(['tagsTranslated','children','parents'])
            ->first();
    }

    public function node(string $key): mixed
    {
        return $this->nodes[$key] ?? null;
    }

    /**
     * Undocumented function
     *
     * @param string ...$keys
     *
     * @return \Illuminate\Support\Collection
     */
    public function relatedNodes(string ...$keys): Collection
    {
        return collect($keys)->mapWithKeys(fn($key) => [$key => $this->relatedNode($key)]);
    }


    public function relatedNode(string $key): ?CmsContentPages
    {
        return CmsContentPages::with(['tagsTranslated','children','parents'])
            ->whereKey($this->node($key))
            ->first();
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $key
     * @param mixed $value
     * @param string|null $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelated(Builder $builder, string $key, $value, ?string $type = null): Builder
    {

        return $builder->whereJsonContains("nodes->{$key}", $value)->when(
            $type,
            fn($query) => $query->whereNamespace($type)
        )->with(['nodes','tagsTranslated','children','parents']);
    }
}