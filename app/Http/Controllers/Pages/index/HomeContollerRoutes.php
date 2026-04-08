<?php

namespace App\Http\Controllers\Pages\index;

use App\Http\Controllers\Controller;
use App\Support\Institutional\InstitutionalPageRegistry;
use App\Support\Monetization\MonetizationPolicy;
use App\Support\Project\ModuleManager;
use App\Support\Seo\SeoBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeContollerRoutes extends Controller
{
    public function index(SeoBuilder $seoBuilder, ModuleManager $moduleManager, InstitutionalPageRegistry $institutionalPageRegistry, MonetizationPolicy $monetizationPolicy)
    {
        $seo = $seoBuilder->buildPage([
            'title' => config('project.name', 'Universal Site Engine'),
            'description' => 'Base mestre para múltiplos sites de conteúdo, ferramentas e híbridos com SEO centralizado e preparo para Adsense.',
            'path' => '/',
            'tags' => ['seo', 'laravel', 'vue', 'inertia', 'adsense', 'modular'],
            'breadcrumb' => [
                ['name' => 'Início', 'url' => route('index.home')],
            ],
            'is_indexable' => true,
        ]);

        return Inertia::render('Pages/index/index', [
            'seo' => $seo,
            'pageType' => 'home',
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'enabledModules' => $moduleManager->enabledModules(),
            'institutionalPages' => $institutionalPageRegistry->enabledPages(),
            'showAdSlots' => $monetizationPolicy->shouldShowSlots('home'),
        ]);
    }
}
