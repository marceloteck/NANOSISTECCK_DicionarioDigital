<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Support\Seo\RobotsBuilder;
use App\Support\Seo\SitemapBuilder;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(RobotsBuilder $robotsBuilder): Response
    {
        return response($robotsBuilder->build(), 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function sitemap(SitemapBuilder $sitemapBuilder): Response
    {
        $xml = view('seo.sitemap', [
            'items' => $sitemapBuilder->build(),
            'lastmod' => now()->toAtomString(),
        ])->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
