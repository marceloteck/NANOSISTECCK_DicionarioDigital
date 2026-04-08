<?php

namespace App\Support\Seo;

class SchemaBuilder
{
    public function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => rtrim((string) config('seo.base_url'), '/').'#website',
            'name' => config('seo.site_name'),
            'url' => config('seo.base_url'),
            'inLanguage' => config('seo.language'),
        ];
    }

    public function organization(): array
    {
        $organization = (array) config('seo.organization', []);

        return [
            '@type' => 'Organization',
            '@id' => rtrim((string) ($organization['url'] ?? config('seo.base_url')), '/').'#organization',
            'name' => $organization['name'] ?? config('seo.site_name'),
            'url' => $organization['url'] ?? config('seo.base_url'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $organization['logo'] ?? config('seo.default_image'),
            ],
            'sameAs' => $organization['sameAs'] ?? [],
        ];
    }

    public function webpage(array $payload): array
    {
        return [
            '@type' => 'WebPage',
            '@id' => $payload['canonical'].'#webpage',
            'url' => $payload['canonical'],
            'name' => $payload['title'],
            'description' => $payload['description'],
            'isPartOf' => ['@id' => rtrim((string) config('seo.base_url'), '/').'#website'],
            'inLanguage' => config('seo.language'),
        ];
    }

    public function article(array $payload): array
    {
        return [
            '@type' => $payload['schema_type'] ?? 'Article',
            '@id' => $payload['canonical'].'#article',
            'headline' => $payload['title'],
            'description' => $payload['description'],
            'image' => [$payload['image']],
            'author' => [
                '@type' => 'Person',
                'name' => $payload['author'] ?: config('seo.author'),
            ],
            'publisher' => ['@id' => rtrim((string) config('seo.base_url'), '/').'#organization'],
            'datePublished' => $payload['published_time'],
            'dateModified' => $payload['modified_time'] ?: $payload['published_time'],
            'mainEntityOfPage' => ['@id' => $payload['canonical'].'#webpage'],
            'articleSection' => $payload['section'],
            'keywords' => $payload['tags'],
        ];
    }

    public function faq(array $faqItems): ?array
    {
        if ($faqItems === []) {
            return null;
        }

        return [
            '@type' => 'FAQPage',
            '@id' => url()->current().'#faq',
            'mainEntity' => array_values(array_map(static fn ($item) => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'],
                ],
            ], $faqItems)),
        ];
    }

    public function breadcrumb(array $items): ?array
    {
        if (count($items) < 2) {
            return null;
        }

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_map(
                static fn ($item, $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'item' => $item['url'] ?? null,
                ],
                $items,
                array_keys($items)
            )),
        ];
    }

    public function tool(array $payload, array $data = []): array
    {
        return [
            '@type' => $data['schema_type'] ?? 'SoftwareApplication',
            '@id' => $payload['canonical'].'#tool',
            'name' => $payload['title'],
            'description' => $payload['description'],
            'url' => $payload['canonical'],
            'applicationCategory' => $data['application_category'] ?? 'DeveloperApplication',
            'operatingSystem' => $data['operating_system'] ?? 'Web',
            'image' => $payload['image'],
        ];
    }


    public function howTo(array $steps, array $payload): ?array
    {
        if ($steps === []) {
            return null;
        }

        $normalized = collect($steps)
            ->filter(fn ($step) => is_array($step) && ! empty($step['name']))
            ->values()
            ->map(fn ($step, $index) => [
                '@type' => 'HowToStep',
                'position' => $index + 1,
                'name' => (string) ($step['name'] ?? ''),
                'text' => (string) ($step['text'] ?? $step['name'] ?? ''),
            ])
            ->all();

        if ($normalized === []) {
            return null;
        }

        return [
            '@type' => 'HowTo',
            '@id' => $payload['canonical'].'#how-to',
            'name' => 'Como usar',
            'step' => $normalized,
        ];
    }

    public function collectionPage(array $payload): array
    {
        return [
            '@type' => 'CollectionPage',
            '@id' => $payload['canonical'].'#collection',
            'name' => $payload['title'],
            'description' => $payload['description'],
            'url' => $payload['canonical'],
            'keywords' => $payload['tags'],
        ];
    }

    public function graph(array $nodes): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter($nodes)),
        ];
    }
}
