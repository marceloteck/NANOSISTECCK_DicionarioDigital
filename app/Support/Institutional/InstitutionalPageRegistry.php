<?php

namespace App\Support\Institutional;

use Illuminate\Support\Arr;

class InstitutionalPageRegistry
{
    public function enabledPages(): array
    {
        return collect((array) config('project.institutional_pages', []))
            ->filter(fn ($config) => (bool) Arr::get((array) $config, 'enabled', false))
            ->map(fn ($config, $key) => [
                'key' => $key,
                'slug' => Arr::get((array) $config, 'slug', $key),
            ])
            ->values()
            ->all();
    }

    public function pageBySlug(string $slug): ?array
    {
        return collect($this->enabledPages())->firstWhere('slug', $slug);
    }
}
