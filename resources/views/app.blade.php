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
        <section
            id="cookie-consent-banner"
            class="cookie-consent-banner"
            role="dialog"
            aria-live="polite"
            aria-label="Aviso de cookies"
            hidden
        >
            <div class="cookie-consent-content">
                <p>
                    Usamos cookies e tecnologias semelhantes para melhorar sua experiência, personalizar conteúdo e exibir anúncios (como Google AdSense).
                    Ao continuar navegando, você concorda com esse uso.
                </p>
                <div class="cookie-consent-actions">
                    <button type="button" id="cookie-consent-accept" class="cookie-btn cookie-btn-primary">OK, entendi</button>
                    <button type="button" id="cookie-consent-close" class="cookie-btn cookie-btn-ghost" aria-label="Fechar aviso de cookies">Fechar</button>
                </div>
            </div>
        </section>
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

            .cookie-consent-banner {
                position: fixed;
                right: 1rem;
                left: 1rem;
                bottom: 1rem;
                z-index: 9999;
                display: flex;
                justify-content: center;
            }

            .cookie-consent-content {
                width: min(780px, 100%);
                background: rgba(17, 24, 39, 0.94);
                color: #f9fafb;
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 16px;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
                padding: 1rem 1.1rem;
                display: grid;
                gap: 0.9rem;
            }

            .cookie-consent-content p {
                margin: 0;
                font-size: 0.95rem;
                line-height: 1.5;
            }

            .cookie-consent-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.6rem;
            }

            .cookie-btn {
                border: 1px solid transparent;
                border-radius: 999px;
                padding: 0.55rem 0.95rem;
                font-size: 0.88rem;
                font-weight: 600;
                cursor: pointer;
            }

            .cookie-btn-primary {
                background: #8b5cf6;
                color: #fff;
            }

            .cookie-btn-ghost {
                background: transparent;
                border-color: rgba(255, 255, 255, 0.42);
                color: #f9fafb;
            }

            @media (max-width: 560px) {
                .cookie-consent-content {
                    border-radius: 14px;
                    padding: 0.9rem;
                }

                .cookie-consent-actions .cookie-btn {
                    width: 100%;
                }
            }
        </style>
        <script>
            (() => {
                const storageKey = 'cookie_consent_accepted_v1';
                const banner = document.getElementById('cookie-consent-banner');
                const acceptBtn = document.getElementById('cookie-consent-accept');
                const closeBtn = document.getElementById('cookie-consent-close');

                if (!banner || !acceptBtn || !closeBtn) return;

                const hasConsent = localStorage.getItem(storageKey) === 'yes';
                if (!hasConsent) {
                    banner.hidden = false;
                }

                const handleClose = () => {
                    localStorage.setItem(storageKey, 'yes');
                    banner.hidden = true;
                };

                acceptBtn.addEventListener('click', handleClose);
                closeBtn.addEventListener('click', handleClose);
            })();
        </script>
    </body>
</html>
