<?php

namespace App\Http\Controllers\Institutional;

use App\Http\Controllers\Controller;
use App\Support\Institutional\InstitutionalPageRegistry;
use App\Support\Monetization\MonetizationPolicy;
use App\Support\Seo\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class InstitutionalPageController extends Controller
{
    public function show(string $slug, InstitutionalPageRegistry $registry, SeoBuilder $seoBuilder, MonetizationPolicy $monetizationPolicy): Response
    {
        $page = $registry->pageBySlug($slug);
        abort_if(! $page, 404);

        $titles = [
            'about' => 'Sobre',
            'contact' => 'Contato',
            'privacy' => 'Política de Privacidade',
            'terms' => 'Termos de Uso',
        ];

        $title = $titles[$page['key']] ?? ucfirst($page['key']);

        $seo = $seoBuilder->buildPage([
            'title' => $title,
            'description' => "Página institucional de {$title} com informações de credibilidade e conformidade.",
            'canonical' => route('institutional.show', $slug),
            'breadcrumb' => [
                ['name' => 'Início', 'url' => route('index.home')],
                ['name' => $title, 'url' => route('institutional.show', $slug)],
            ],
            'is_indexable' => true,
        ]);

        return Inertia::render('Pages/institutional/show', [
            'seo' => $seo,
            'pageType' => 'institutional',
            'institutionalPage' => [
                'key' => $page['key'],
                'slug' => $page['slug'],
                'title' => $title,
            ],
            'showAdSlots' => $monetizationPolicy->shouldShowSlots('institutional'),
        ]);
    }
}
