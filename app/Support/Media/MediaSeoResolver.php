<?php

namespace App\Support\Media;

use App\Support\Seo\CanonicalUrl;
use Illuminate\Support\Arr;

class MediaSeoResolver
{
    public function __construct(protected ?CanonicalUrl $canonicalUrl = null)
    {
        $this->canonicalUrl ??= new CanonicalUrl();
    }

    public function resolveImage(?string $image, string $pageType = 'home'): string
    {
        $fallback = Arr::get(config('project.media.page_type_images', []), $pageType)
            ?: config('project.branding.default_social_image')
            ?: config('project.media.default_image')
            ?: config('seo.default_image');

        return $this->canonicalUrl->normalize((string) ($image ?: $fallback));
    }

    public function resolveAlt(?string $alt, ?string $title = null): string
    {
        return trim((string) ($alt ?: $title ?: config('project.media.fallback_alt', config('project.name', 'Imagem do projeto'))));
    }

    public function branding(): array
    {
        return [
            'logo' => $this->canonicalUrl->normalize((string) config('project.branding.logo', config('seo.organization.logo'))),
            'favicon' => $this->canonicalUrl->normalize((string) config('project.branding.favicon', config('seo.favicon'))),
            'default_social_image' => $this->canonicalUrl->normalize((string) config('project.branding.default_social_image', config('seo.default_image'))),
        ];
    }
}
