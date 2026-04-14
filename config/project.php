<?php

return [
    'name' => env('PROJECT_NAME', env('APP_NAME', 'Universal Site Engine')),
    'organization' => env('PROJECT_ORGANIZATION', 'NANOSISTECCK'),
    'project_type' => env('PROJECT_TYPE', 'content'), // content | tools | hybrid | custom

    'modules' => [
        'posts' => filter_var(env('PROJECT_MODULE_POSTS', true), FILTER_VALIDATE_BOOLEAN),
        'tools' => filter_var(env('PROJECT_MODULE_TOOLS', false), FILTER_VALIDATE_BOOLEAN),
        'taxonomy' => filter_var(env('PROJECT_MODULE_TAXONOMY', true), FILTER_VALIDATE_BOOLEAN),
        'faq' => filter_var(env('PROJECT_MODULE_FAQ', true), FILTER_VALIDATE_BOOLEAN),
        'related' => filter_var(env('PROJECT_MODULE_RELATED', true), FILTER_VALIDATE_BOOLEAN),
        'breadcrumbs' => filter_var(env('PROJECT_MODULE_BREADCRUMBS', true), FILTER_VALIDATE_BOOLEAN),
        'monetization' => filter_var(env('PROJECT_MODULE_MONETIZATION', true), FILTER_VALIDATE_BOOLEAN),
        'institutional_pages' => filter_var(env('PROJECT_MODULE_INSTITUTIONAL_PAGES', true), FILTER_VALIDATE_BOOLEAN),
        'search_page' => filter_var(env('PROJECT_MODULE_SEARCH_PAGE', true), FILTER_VALIDATE_BOOLEAN),
        'media_seo' => filter_var(env('PROJECT_MODULE_MEDIA_SEO', true), FILTER_VALIDATE_BOOLEAN),
    ],

    'project_profiles' => [
        'content' => [
            'layout' => 'content',
            'modules' => ['posts', 'taxonomy', 'faq', 'related', 'breadcrumbs', 'monetization', 'institutional_pages', 'search_page', 'media_seo'],
            'preferred_schema' => ['WebPage', 'Article', 'CollectionPage'],
            'seo_strategy' => 'editorial',
        ],
        'tools' => [
            'layout' => 'tools',
            'modules' => ['tools', 'faq', 'related', 'breadcrumbs', 'monetization', 'institutional_pages', 'search_page', 'media_seo'],
            'preferred_schema' => ['WebPage', 'SoftwareApplication', 'FAQPage'],
            'seo_strategy' => 'transactional',
        ],
        'hybrid' => [
            'layout' => 'hybrid',
            'modules' => ['posts', 'tools', 'taxonomy', 'faq', 'related', 'breadcrumbs', 'monetization', 'institutional_pages', 'search_page', 'media_seo'],
            'preferred_schema' => ['WebPage', 'Article', 'SoftwareApplication', 'CollectionPage'],
            'seo_strategy' => 'mixed',
        ],
        'custom' => [
            'layout' => 'default',
            'modules' => [],
            'preferred_schema' => ['WebPage'],
            'seo_strategy' => 'custom',
        ],
    ],

    'layouts' => [
        'default' => 'default',
        'content' => 'content',
        'tools' => 'tools',
        'hybrid' => 'hybrid',
        'institutional' => 'institutional',
    ],

    'institutional_pages' => [
        'about' => ['enabled' => filter_var(env('PROJECT_PAGE_ABOUT', true), FILTER_VALIDATE_BOOLEAN), 'slug' => 'sobre'],
        'contact' => ['enabled' => filter_var(env('PROJECT_PAGE_CONTACT', true), FILTER_VALIDATE_BOOLEAN), 'slug' => 'contato'],
        'privacy' => ['enabled' => filter_var(env('PROJECT_PAGE_PRIVACY', true), FILTER_VALIDATE_BOOLEAN), 'slug' => 'politica-de-privacidade'],
        'terms' => ['enabled' => filter_var(env('PROJECT_PAGE_TERMS', true), FILTER_VALIDATE_BOOLEAN), 'slug' => 'termos-de-uso'],
    ],

    'taxonomy' => [
        'categories' => filter_var(env('PROJECT_TAXONOMY_CATEGORIES', true), FILTER_VALIDATE_BOOLEAN),
        'tags' => filter_var(env('PROJECT_TAXONOMY_TAGS', true), FILTER_VALIDATE_BOOLEAN),
        'allow_tools_taxonomy' => filter_var(env('PROJECT_TAXONOMY_TOOLS', true), FILTER_VALIDATE_BOOLEAN),
    ],

    'monetization' => [
        'adsense_ready' => filter_var(env('PROJECT_ADSENSE_READY', true), FILTER_VALIDATE_BOOLEAN),
        'show_ad_slots' => filter_var(env('PROJECT_SHOW_AD_SLOTS', true), FILTER_VALIDATE_BOOLEAN),
        'allowed_positions' => ['top', 'middle', 'bottom', 'sidebar', 'in_feed'],
        'disabled_page_types' => ['institutional', 'error'],
        'per_page_type' => [
            'home' => ['in_feed'],
            'post' => ['top', 'middle', 'bottom', 'in_feed'],
            'category' => ['in_feed', 'sidebar'],
            'tag' => ['in_feed', 'sidebar'],
            'tool' => ['top', 'middle', 'bottom'],
            'listing' => ['in_feed', 'sidebar'],
            'institutional' => [],
            'error' => [],
        ],
    ],

    'branding' => [
        'logo' => env('PROJECT_LOGO', '/images/seo/logo.png'),
        'favicon' => env('PROJECT_FAVICON', '/favicon.svg'),
        'default_social_image' => env('PROJECT_DEFAULT_SOCIAL_IMAGE', '/images/seo/default.jpg'),
    ],

    'media' => [
        'default_image' => env('PROJECT_DEFAULT_IMAGE', '/images/seo/default.jpg'),
        'fallback_alt' => env('PROJECT_FALLBACK_ALT', 'Imagem ilustrativa do site'),
        'page_type_images' => [
            'home' => env('PROJECT_IMAGE_HOME', '/images/seo/home.jpg'),
            'post' => env('PROJECT_IMAGE_POST', '/images/seo/post.jpg'),
            'tool' => env('PROJECT_IMAGE_TOOL', '/images/seo/tool.jpg'),
            'institutional' => env('PROJECT_IMAGE_INSTITUTIONAL', '/images/seo/institutional.jpg'),
            'listing' => env('PROJECT_IMAGE_LISTING', '/images/seo/listing.jpg'),
        ],
    ],

    'indexation' => [
        'default_indexable' => filter_var(env('PROJECT_DEFAULT_INDEXABLE', true), FILTER_VALIDATE_BOOLEAN),
        'page_type' => [
            'home' => true,
            'post' => true,
            'category' => true,
            'tag' => true,
            'tool' => true,
            'listing' => true,
            'institutional' => true,
            'error' => false,
            'search' => false,
        ],
    ],
];
