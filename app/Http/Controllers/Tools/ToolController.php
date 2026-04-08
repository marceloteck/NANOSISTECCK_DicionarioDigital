<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Tools\Tool;
use App\Support\Listing\ListingSeoFactory;
use App\Support\Media\MediaSeoResolver;
use App\Support\Monetization\MonetizationPolicy;
use App\Support\Related\RelatedContentResolver;
use App\Support\Seo\SeoBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ToolController extends Controller
{
    public function index(Request $request, ListingSeoFactory $listingSeoFactory): Response
    {
        $page = max(1, (int) $request->integer('page', 1));

        $tools = Tool::query()
            ->published()
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        $seo = $listingSeoFactory->build(
            title: 'Ferramentas',
            description: 'Ferramentas utilitárias e páginas de aplicação prontas para SEO técnico e monetização sustentável.',
            canonical: route('tools.index'),
            page: $page,
            breadcrumb: [
                ['name' => 'Início', 'url' => route('index.home')],
                ['name' => 'Ferramentas', 'url' => route('tools.index')],
            ],
        );

        return Inertia::render('Pages/tools/index', [
            'seo' => $seo,
            'tools' => $tools,
            'pageType' => 'listing',
        ]);
    }

    public function show(Tool $tool, SeoBuilder $seoBuilder, RelatedContentResolver $relatedContentResolver, MonetizationPolicy $monetizationPolicy, MediaSeoResolver $mediaSeoResolver): Response
    {
        abort_unless($tool->is_published, 404);

        $seo = $seoBuilder->buildTool([
            'title' => $tool->seo_title ?: $tool->title,
            'description' => $tool->meta_description ?: ($tool->excerpt ?: 'Ferramenta prática para acelerar tarefas e melhorar produtividade.'),
            'canonical' => $tool->canonical_url ?: route('tools.show', $tool),
            'image' => $mediaSeoResolver->resolveImage($tool->featured_image, 'tool'),
            'type' => 'website',
            'is_indexable' => $tool->is_indexable,
            'faq_json' => $tool->faq_json ?? [],
            'breadcrumb' => [
                ['name' => 'Início', 'url' => route('index.home')],
                ['name' => 'Ferramentas', 'url' => route('tools.index')],
                ['name' => $tool->title, 'url' => route('tools.show', $tool)],
            ],
            'application_category' => 'UtilitiesApplication',
            'operating_system' => 'Web',
        ]);

        return Inertia::render('Pages/tools/show', [
            'seo' => $seo,
            'tool' => $tool,
            'relatedItems' => $relatedContentResolver->relatedForTool($tool),
            'pageType' => 'tool',
            'adSlots' => $monetizationPolicy->slotsFor('tool'),
            'showAdSlots' => $monetizationPolicy->shouldShowSlots('tool', (bool) $tool->is_indexable),
        ]);
    }
}
