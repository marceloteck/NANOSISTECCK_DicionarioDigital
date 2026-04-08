<!DOCTYPE html>
<html lang="{{ config('seo.language', str_replace('_', '-', app()->getLocale())) }}">
    <head>
        <meta charset="{{ config('seo.charset', 'utf-8') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="{{ config('seo.theme_color') }}">
        <meta name="referrer" content="strict-origin-when-cross-origin">
        <link rel="icon" href="{{ config('seo.favicon') }}">

        <title inertia>{{ config('seo.title_default', config('app.name', 'Inertia_Laravel')) }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <!-- Styles -->
        @include('AssetsGlobal/globalCss')
        @production
            @include('configApp/analytics')
        @endproduction

        <!-- Scripts -->
        @routes
        @vite(['resources/js/config/app.js', "resources/PagesVuejs/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body>
        <a href="#app-content" class="skip-link">Pular para conteúdo principal</a>
        <main id="app-content">
            @inertia
        </main>
        @include('AssetsGlobal/globalJs')

        <style>
            .skip-link {
                position: absolute;
                left: -9999px;
                top: 1rem;
                z-index: 9999;
                background: #111827;
                color: #ffffff;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
            }

            .skip-link:focus {
                left: 1rem;
            }
        </style>
    </body>
</html>
