<?php

namespace App\Support\Project;

use Illuminate\Support\Arr;

class PageTypeRegistry
{
    public function layoutFor(string $pageType): string
    {
        $profileLayout = app(ProjectProfile::class)->preferredLayout();
        $layouts = (array) config('project.layouts', []);

        return match ($pageType) {
            'institutional' => (string) Arr::get($layouts, 'institutional', 'institutional'),
            'post', 'category', 'tag' => app(ModuleManager::class)->enabled('posts') ? (string) Arr::get($layouts, 'content', $profileLayout) : $profileLayout,
            'tool' => app(ModuleManager::class)->enabled('tools') ? (string) Arr::get($layouts, 'tools', $profileLayout) : $profileLayout,
            default => (string) Arr::get($layouts, $profileLayout, $profileLayout),
        };
    }

    public function indexable(string $pageType): bool
    {
        return (bool) Arr::get(config('project.indexation.page_type', []), $pageType, config('project.indexation.default_indexable', true));
    }
}
