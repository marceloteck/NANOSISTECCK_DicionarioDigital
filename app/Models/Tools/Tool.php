<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'seo_title',
        'meta_description',
        'canonical_url',
        'featured_image',
        'featured_image_alt',
        'faq_json',
        'how_to_steps',
        'is_published',
        'is_indexable',
    ];

    protected $casts = [
        'faq_json' => 'array',
        'how_to_steps' => 'array',
        'is_published' => 'boolean',
        'is_indexable' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $tool): void {
            if ($tool->slug === null || $tool->slug === '') {
                $tool->slug = Str::slug($tool->title);
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeIndexable(Builder $query): Builder
    {
        return $query->where('is_indexable', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
