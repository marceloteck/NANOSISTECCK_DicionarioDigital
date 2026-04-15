<?php

namespace App\Support\Posts;

use App\Models\PostCategory;
use Illuminate\Support\Str;

class PostCategoryResolver
{
    public function resolveId(?string $categoryName, ?int $legacyCategoryId = null): ?int
    {
        $normalizedName = $this->normalizeName($categoryName);

        if ($normalizedName === null) {
            return $legacyCategoryId;
        }

        $normalizedKey = $this->normalizeKey($normalizedName);

        $existing = PostCategory::query()
            ->get(['id', 'name'])
            ->first(fn (PostCategory $category) => $this->normalizeKey($category->name) === $normalizedKey);

        if ($existing) {
            return $existing->id;
        }

        $baseSlug = Str::slug($normalizedName) ?: 'categoria';
        $slug = $baseSlug;
        $counter = 2;

        while (PostCategory::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $created = PostCategory::query()->create([
            'name' => $normalizedName,
            'slug' => $slug,
        ]);

        return $created->id;
    }

    public function normalizeName(?string $categoryName): ?string
    {
        if ($categoryName === null) {
            return null;
        }

        $cleaned = preg_replace('/\s+/u', ' ', trim($categoryName));
        $cleaned = is_string($cleaned) ? trim($cleaned) : '';

        return $cleaned === '' ? null : $cleaned;
    }

    public function normalizeKey(string $value): string
    {
        $squashed = preg_replace('/\s+/u', ' ', trim($value));
        $squashed = is_string($squashed) ? $squashed : $value;

        return Str::lower(Str::ascii($squashed));
    }
}
