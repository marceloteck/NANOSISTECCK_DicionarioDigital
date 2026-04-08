<?php

namespace App\Support\Seo;

class RobotsBuilder
{
    public function build(): string
    {
        $indexationEnabled = (bool) config('seo.indexation.enabled', true);
        $allowedEnvironments = (array) config('seo.indexation.allow_in_env', ['production']);
        $canIndex = $indexationEnabled && in_array(app()->environment(), $allowedEnvironments, true);

        $lines = ['User-agent: *'];

        if (! $canIndex) {
            $lines[] = 'Disallow: /';

            return implode(PHP_EOL, $lines).PHP_EOL;
        }

        foreach ((array) config('seo.robots.allow_paths', ['/']) as $path) {
            $lines[] = 'Allow: '.(string) $path;
        }

        foreach ((array) config('seo.robots.disallow_paths', []) as $path) {
            $lines[] = 'Disallow: '.(string) $path;
        }

        $lines[] = 'Sitemap: '.url('/sitemap.xml');

        return implode(PHP_EOL, $lines).PHP_EOL;
    }
}
