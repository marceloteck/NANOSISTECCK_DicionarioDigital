# 03) Arquitetura Backend (Laravel)

## Camadas principais

- **Controllers:** orquestram fluxo HTTP + Inertia
- **Models:** encapsulam regras de persistência e scopes
- **Support:** regras de domínio (SEO, projeto, mídia, monetização, posts)
- **Requests:** validação de entrada para criação/edição

## Controllers e responsabilidades

- `HomeContollerRoutes`: homepage com módulos ativos, SEO e ad slots
- `PostController`: listagem, detalhe, categoria, tag, create/update
- `ToolController`: listagem e detalhe de ferramentas
- `SearchController`: busca em posts e tools
- `InstitutionalPageController`: páginas institucionais dinâmicas
- `SeoController`: endpoints de `robots.txt` e `sitemap.xml`

## Models

- `Post`
  - gera slug automaticamente
  - calcula `reading_time`
  - sincroniza `status` e `is_published`
  - scopes: `published()`, `indexable()`
- `PostCategory`
- `PostTag`
- `Tool`
  - gera slug automaticamente
  - scopes: `published()`, `indexable()`

## Domínio em `app/Support`

### Projeto
- `ProjectProfile`: resolve tipo/perfil
- `ModuleManager`: resolve módulos habilitados
- `PageTypeRegistry`: layout e indexação por tipo de página

### SEO
- `SeoBuilder`: payload SEO padrão para páginas, posts, categorias e tools
- `SchemaBuilder`: JSON-LD (`WebPage`, `Article`, `CollectionPage`, `SoftwareApplication`, etc.)
- `SitemapBuilder`: geração de URLs indexáveis
- `RobotsBuilder`: regras de robots por config
- `PostSeoMapper`: mapeia Post para payload SEO

### Conteúdo e descoberta
- `PostCreator`: criação/atualização segura de posts
- `PostContentFormatter`: formatação, TOC e FAQ
- `PostTitleTemplate`: sugestões de títulos
- `RelatedContentResolver` / `PostRelatedResolver`: conteúdo relacionado

### Outros
- `MediaSeoResolver`: imagem de fallback por tipo de página
- `MonetizationPolicy`: slots e regras de exibição
- `InstitutionalPageRegistry`: registro central de páginas institucionais

## Rotas web condicionais

As rotas são ativadas/desativadas dinamicamente com base em `project.modules.*`:
- `/posts`, `/posts/{slug}`
- `/categoria/{slug}` e `/tag/{slug}`
- `/tools`, `/tools/{slug}`
- `/institucional/{slug}`
- `/buscar`
- `/robots.txt`, `/sitemap.xml`

