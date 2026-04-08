# Universal Site Engine (Laravel + Vue + Inertia)

## Tipos de projeto
- `content`: foco editorial com posts/taxonomias.
- `tools`: foco em páginas de ferramentas utilitárias.
- `hybrid`: mistura conteúdo + ferramentas para aquisição e retenção.
- `custom`: perfil livre.

## Core universal
- `config/project.php`: chave central de comportamento por projeto.
- `App\Support\Project\ProjectProfile`: resolve perfil ativo.
- `App\Support\Project\ModuleManager`: ativa/desativa módulos por projeto.
- `App\Support\Project\PageTypeRegistry`: define layout/indexação por tipo de página.

## Módulos opcionais
Ativados em `project.modules`:
- `posts`
- `tools`
- `taxonomy`
- `faq`
- `related`
- `breadcrumbs`
- `monetization`
- `institutional_pages`
- `search_page`
- `media_seo`

## SEO, mídia e monetização
- SEO centralizado em `SeoBuilder`.
- Mídia SEO centralizada em `MediaSeoResolver`.
- Regras de monetização em `MonetizationPolicy`.
- Sitemap inclui posts, taxonomias, tools e institucionais conforme módulos ativos.

## Base de ferramentas
- Model: `App\Models\Tools\Tool`
- Migration: `create_tools_table`
- Rotas: `/tools`, `/tools/{slug}` (somente quando módulo tools está ativo)
- SEO: `SeoBuilder::buildTool()` com `SoftwareApplication/WebApplication`, FAQ e HowTo opcionais.

## Páginas institucionais
- Rota única dinâmica: `/institucional/{slug}`.
- Registro central: `InstitutionalPageRegistry`.
- Páginas padrão: sobre, contato, política de privacidade, termos de uso.

## Search/listing base
- Busca inicial: `/buscar?q=...` (noindex por padrão).
- Listagens prontas: posts, taxonomias e tools.

## Como criar novo site
1. Ajuste `PROJECT_TYPE` e chaves de `PROJECT_MODULE_*` no `.env`.
2. Configure branding e mídia em `config/project.php` ou variáveis `PROJECT_*`.
3. Publique conteúdos/posts e (opcionalmente) tools.
4. Ajuste layouts base em `resources/PagesVuejs/components/Layouts/*`.

## Exemplos práticos
### 1) Site de conteúdo
```env
PROJECT_TYPE=content
PROJECT_MODULE_POSTS=true
PROJECT_MODULE_TOOLS=false
PROJECT_MODULE_TAXONOMY=true
```

### 2) Site de ferramentas
```env
PROJECT_TYPE=tools
PROJECT_MODULE_POSTS=false
PROJECT_MODULE_TOOLS=true
PROJECT_MODULE_TAXONOMY=false
```

### 3) Site híbrido
```env
PROJECT_TYPE=hybrid
PROJECT_MODULE_POSTS=true
PROJECT_MODULE_TOOLS=true
PROJECT_MODULE_TAXONOMY=true
```
