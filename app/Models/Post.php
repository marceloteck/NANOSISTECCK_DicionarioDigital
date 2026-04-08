<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content_html',
        'featured_image',
        'featured_image_alt',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'schema_type',
        'search_intent',
        'content_type',
        'category_id',
        'author_name',
        'published_at',
        'is_published',
        'is_indexable',
        'reading_time',
        'faq_json',
        'related_keywords',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_indexable' => 'boolean',
        'faq_json' => 'array',
        'related_keywords' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $post): void {
            if ($post->slug === null || $post->slug === '') {
                $post->slug = Str::slug($post->title);
            }

            $post->reading_time = max(1, (int) ceil(str_word_count(strip_tags((string) $post->content_html)) / 220));

            if ($post->status === 'published' && ! $post->published_at) {
                $post->published_at = now();
            }

            if ($post->is_published && $post->status !== 'published') {
                $post->status = 'published';
            }

            if (! $post->is_published && $post->status === 'published') {
                $post->status = 'draft';
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PostTag::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeIndexable(Builder $query): Builder
    {
        return $query->where('is_indexable', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPublicUrlAttribute(): string
    {
        return route('posts.show', $this);
    }

    public function getPublishedAtIsoAttribute(): ?string
    {
        return $this->published_at?->toAtomString();
    }

    public function getUpdatedAtIsoAttribute(): ?string
    {
        return $this->updated_at?->toAtomString();
    }
}
