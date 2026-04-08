<?php

namespace App\Support\Seo;

class CanonicalUrl
{
    public function normalize(string $url, ?string $baseUrl = null): string
    {
        if ($url === '') {
            return '';
        }

        $baseUrl ??= (string) config('seo.base_url', config('app.url'));

        $absoluteUrl = preg_match('/^https?:\/\//i', $url)
            ? $url
            : rtrim($baseUrl, '/').'/'.ltrim($url, '/');

        $parts = parse_url($absoluteUrl);

        if (! $parts || empty($parts['host'])) {
            return rtrim($absoluteUrl, '/');
        }

        $query = [];
        if (! empty($parts['query'])) {
            parse_str($parts['query'], $query);
            foreach (array_keys($query) as $key) {
                if (preg_match('/^(utm_|fbclid|gclid|msclkid)/i', (string) $key)) {
                    unset($query[$key]);
                }
            }
        }

        $normalizedPath = '/'.ltrim($parts['path'] ?? '/', '/');
        if ($normalizedPath !== '/') {
            $normalizedPath = rtrim($normalizedPath, '/');
        }

        $normalizedUrl = sprintf(
            '%s://%s%s%s%s',
            $parts['scheme'] ?? 'https',
            $parts['host'],
            isset($parts['port']) ? ':'.$parts['port'] : '',
            $normalizedPath,
            empty($query) ? '' : '?'.http_build_query($query)
        );

        return $normalizedUrl;
    }
}
