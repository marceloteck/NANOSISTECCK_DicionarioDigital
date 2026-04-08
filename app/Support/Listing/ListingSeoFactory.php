<?php

namespace App\Support\Listing;

use App\Support\Seo\SeoBuilder;

class ListingSeoFactory
{
    public function __construct(protected SeoBuilder $seoBuilder)
    {
    }

    public function build(string $title, string $description, string $canonical, int $page = 1, array $breadcrumb = []): array
    {
        return $this->seoBuilder->buildCategory([
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'noindex' => $page > 1,
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
