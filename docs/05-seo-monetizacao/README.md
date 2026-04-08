# 05) SEO, Schema, Sitemap, Robots e Monetização

## Estratégia SEO centralizada

O projeto usa `SeoBuilder` para manter consistência entre páginas:
- title/description/canonical
- robots/noindex
- imagem social com fallback por tipo
- schema graph JSON-LD
- slots de monetização

## Schema gerado

Dependendo da página, são combinados nós como:
- `WebSite`
- `Organization`
- `WebPage`
- `Article`
- `CollectionPage`
- `SoftwareApplication` / `WebApplication`
- `FAQPage`
- `HowTo`
- `BreadcrumbList`

## Sitemap dinâmico

`/sitemap.xml` é gerado por `SitemapBuilder` e inclui apenas conteúdo publicável/indexável:
- home
- listagens habilitadas
- posts publicados e indexáveis
- categorias/tags indexáveis
- tools publicadas e indexáveis
- páginas institucionais habilitadas

## Robots dinâmico

`/robots.txt` respeita:
- `SEO_INDEXATION_ENABLED`
- `SEO_INDEXATION_ALLOW_IN_ENV`
- `SEO_ROBOTS_DISALLOW_PATHS`
- `SEO_ROBOTS_ALLOW_PATHS`

## Regras de indexação

- página 2+ de paginação pode receber `noindex` para evitar canibalização
- `search` é `noindex` por padrão
- conteúdos com `is_indexable = false` também recebem `noindex`

## Monetização

`MonetizationPolicy` define:
- se slots podem aparecer por tipo de página,
- posições permitidas (`top`, `middle`, `bottom`, `sidebar`, `in_feed`),
- tipos bloqueados (`institutional`, `error`),
- condicionais por ambiente/indexabilidade.

