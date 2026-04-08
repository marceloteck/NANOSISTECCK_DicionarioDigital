# 02) Setup e Configuração

## Pré-requisitos

- PHP 8.1+
- Composer 2+
- Node.js 18+
- NPM
- MySQL (ou ajuste de DB no `.env`)

## Instalação local (passo a passo)

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run dev
php artisan serve
```

## Scripts úteis

### PHP/Laravel
```bash
php artisan serve
php artisan migrate
php artisan db:seed
php artisan test
```

### Frontend
```bash
npm run dev
npm run build
npm run watch
```

## Variáveis de ambiente essenciais

### Aplicação
- `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL`

### Banco
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

### Projeto modular
> Variáveis lidas por `config/project.php`.

- `PROJECT_NAME`, `PROJECT_ORGANIZATION`, `PROJECT_TYPE`
- `PROJECT_MODULE_POSTS`
- `PROJECT_MODULE_TOOLS`
- `PROJECT_MODULE_TAXONOMY`
- `PROJECT_MODULE_FAQ`
- `PROJECT_MODULE_RELATED`
- `PROJECT_MODULE_BREADCRUMBS`
- `PROJECT_MODULE_MONETIZATION`
- `PROJECT_MODULE_INSTITUTIONAL_PAGES`
- `PROJECT_MODULE_SEARCH_PAGE`
- `PROJECT_MODULE_MEDIA_SEO`

### SEO
> Variáveis lidas por `config/seo.php` e consumidas por os builders de SEO.

- `SEO_SITE_NAME`, `SEO_BASE_URL`, `SEO_TITLE_DEFAULT`
- `SEO_DESCRIPTION_DEFAULT`, `SEO_KEYWORDS_DEFAULT`
- `SEO_LANGUAGE`, `SEO_LOCALE`, `SEO_CHARSET`
- `SEO_INDEXATION_ENABLED`, `SEO_INDEXATION_ALLOW_IN_ENV`
- `SEO_ROBOTS_DISALLOW_PATHS`, `SEO_ROBOTS_ALLOW_PATHS`
- `SEO_ADSENSE_ENABLED`, `SEO_ADSENSE_SHOW_ADS_ONLY_IN_ENV`, `SEO_ADSENSE_REQUIRE_INDEXABLE_PAGE`

## Configuração por perfil

### Exemplo: site de conteúdo
```env
PROJECT_TYPE=content
PROJECT_MODULE_POSTS=true
PROJECT_MODULE_TOOLS=false
PROJECT_MODULE_TAXONOMY=true
```

### Exemplo: site de ferramentas
```env
PROJECT_TYPE=tools
PROJECT_MODULE_POSTS=false
PROJECT_MODULE_TOOLS=true
PROJECT_MODULE_TAXONOMY=false
```

### Exemplo: site híbrido
```env
PROJECT_TYPE=hybrid
PROJECT_MODULE_POSTS=true
PROJECT_MODULE_TOOLS=true
PROJECT_MODULE_TAXONOMY=true
```

