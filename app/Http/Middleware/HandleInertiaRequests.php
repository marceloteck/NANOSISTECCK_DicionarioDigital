<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $seo = config('seo');
        $project = config('project');

        return [
            ...parent::share($request),
            'base_url' => $seo['base_url'] ?? config('app.url'),
            'current_url' => $request->fullUrl(),
            'environment' => app()->environment(),
            'seoDefaults' => [
                'site_name' => $seo['site_name'] ?? null,
                'site_short_name' => $seo['site_short_name'] ?? null,
                'base_url' => $seo['base_url'] ?? config('app.url'),
                'title_default' => $seo['title_default'] ?? null,
                'title_separator' => $seo['title_separator'] ?? ' | ',
                'description_default' => $seo['description_default'] ?? null,
                'keywords_default' => $seo['keywords_default'] ?? [],
                'language' => $seo['language'] ?? null,
                'locale' => $seo['locale'] ?? null,
                'charset' => $seo['charset'] ?? null,
                'author' => $seo['author'] ?? null,
                'publisher' => $seo['publisher'] ?? null,
                'theme_color' => $seo['theme_color'] ?? null,
                'background_color' => $seo['background_color'] ?? null,
                'favicon' => $seo['favicon'] ?? null,
                'default_image' => $seo['default_image'] ?? null,
                'twitter' => $seo['twitter'] ?? [],
                'social_links' => $seo['social_links'] ?? [],
                'organization' => $seo['organization'] ?? [],
                'indexation' => $seo['indexation'] ?? [],
                'robots' => $seo['robots'] ?? [],
                'adsense' => $seo['adsense'] ?? [],
            ],

            'project' => [
                'name' => $project['name'] ?? config('app.name'),
                'type' => $project['project_type'] ?? 'content',
                'modules' => $project['modules'] ?? [],
                'layouts' => $project['layouts'] ?? [],
                'branding' => $project['branding'] ?? [],
                'monetization' => $project['monetization'] ?? [],
                'institutional_pages' => $project['institutional_pages'] ?? [],
                'taxonomy' => $project['taxonomy'] ?? [],
            ],
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
