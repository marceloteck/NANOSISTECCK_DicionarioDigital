<?php

$normalizeList = static function (?string $value): array {
    if (! $value) {
        return [];
    }

    return array_values(array_filter(array_map('trim', explode(',', $value))));
};

return [
    'site_name' => env('SEO_SITE_NAME', env('APP_NAME', 'Site Name')),
    'site_short_name' => env('SEO_SITE_SHORT_NAME', env('APP_NAME', 'Site')),
    'base_url' => env('SEO_BASE_URL', env('APP_URL')),
    'title_default' => env('SEO_TITLE_DEFAULT', env('APP_NAME', 'Site Name')),
    'title_separator' => env('SEO_TITLE_SEPARATOR', ' | '),
    'description_default' => env('SEO_DESCRIPTION_DEFAULT', ''),
    'keywords_default' => $normalizeList(env('SEO_KEYWORDS_DEFAULT', '')),
    'language' => env('SEO_LANGUAGE', 'pt-BR'),
    'locale' => env('SEO_LOCALE', config('app.locale', 'pt-br')),
    'charset' => env('SEO_CHARSET', 'utf-8'),
    'author' => env('SEO_AUTHOR', ''),
    'publisher' => env('SEO_PUBLISHER', ''),
    'theme_color' => env('SEO_THEME_COLOR', '#111827'),
    'background_color' => env('SEO_BACKGROUND_COLOR', '#ffffff'),
    'favicon' => env('SEO_FAVICON', '/favicon.svg'),
    'default_image' => env('SEO_DEFAULT_IMAGE', '/images/seo/default.jpg'),

    'twitter' => [
        'site' => env('SEO_TWITTER_SITE', ''),
        'creator' => env('SEO_TWITTER_CREATOR', ''),
        'card' => env('SEO_TWITTER_CARD', 'summary_large_image'),
    ],

    'social_links' => $normalizeList(env('SEO_SOCIAL_LINKS', '')),

    'organization' => [
        'name' => env('SEO_ORGANIZATION_NAME', env('APP_NAME', 'Site Name')),
        'logo' => env('SEO_ORGANIZATION_LOGO', '/images/seo/logo.png'),
        'url' => env('SEO_ORGANIZATION_URL', env('APP_URL')),
        'sameAs' => $normalizeList(env('SEO_ORGANIZATION_SAME_AS', env('SEO_SOCIAL_LINKS', ''))),
        'contact' => [
            'email' => env('SEO_ORGANIZATION_CONTACT_EMAIL', ''),
            'phone' => env('SEO_ORGANIZATION_CONTACT_PHONE', ''),
            'type' => env('SEO_ORGANIZATION_CONTACT_TYPE', 'customer support'),
        ],
    ],

    'indexation' => [
        'enabled' => filter_var(env('SEO_INDEXATION_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'allow_in_env' => $normalizeList(env('SEO_INDEXATION_ALLOW_IN_ENV', 'production')),
    ],

    'robots' => [
        'disallow_paths' => $normalizeList(env('SEO_ROBOTS_DISALLOW_PATHS', '/login,/admin,/dashboard,/api,/storage,/vendor')),
        'allow_paths' => $normalizeList(env('SEO_ROBOTS_ALLOW_PATHS', '/')),
    ],

    'adsense' => [
        'enabled' => filter_var(env('SEO_ADSENSE_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'show_ads_only_in_env' => $normalizeList(env('SEO_ADSENSE_SHOW_ADS_ONLY_IN_ENV', 'production')),
        'require_indexable_page' => filter_var(env('SEO_ADSENSE_REQUIRE_INDEXABLE_PAGE', true), FILTER_VALIDATE_BOOLEAN),
        'layout_style' => env('SEO_ADSENSE_LAYOUT_STYLE', 'clean'),
        'content_policy' => [
            'avoid_duplicate_content' => true,
            'avoid_spam_patterns' => true,
            'require_clear_navigation' => true,
            'require_readable_layout' => true,
        ],
    ],
];
