# Sistema de Postagens SEO-first

## Criar post (exemplo rápido)

```php
use App\Support\Posts\PostCreator;

$post = app(PostCreator::class)->create([
  'title' => 'Como melhorar SEO técnico',
  'content_html' => '<h2>Contexto</h2><p>...</p><h2>Passos</h2><p>...</p>',
  'excerpt' => 'Checklist real para SEO técnico.',
  'search_intent' => 'tutorial',
  'status' => 'published',
  'is_published' => true,
  'is_indexable' => true,
  'faq_json' => [
    ['question' => 'Funciona com qualquer nicho?', 'answer' => 'Sim, a arquitetura é agnóstica.'],
  ],
], auth()->id());
```

## Atualizar post (exemplo rápido)

```php
$post = app(PostCreator::class)->update($post, [
  'title' => 'Como melhorar SEO técnico em 2026',
  'content_html' => '<h2>Nova versão</h2><p>Conteúdo atualizado.</p>',
  'is_published' => true,
], auth()->id());
```

## Estrutura recomendada de `content_html`
- H2 para seções principais.
- H3 para subtópicos.
- Parágrafos curtos.
- Listas para escaneabilidade.
- Blocos de FAQ no `faq_json`.

## SEO automático dos posts
- `SeoBuilder::buildPost()` usa `PostSeoMapper`.
- Fallback de `title` e `description` quando necessário.
- Gera schema `Article`, `BreadcrumbList` e `FAQPage` (opcional).
- Respeita `is_indexable` para robôs/indexação.
- Em paginação de listagens (page > 1), as páginas são marcadas como `noindex` para evitar canibalização.

## Sitemap
- Inclui apenas posts `published + indexable`.
- Inclui taxonomias indexáveis de categoria/tag.

## Pendências futuras recomendadas
- CRUD administrativo completo (UI Inertia para criação/edição).
- Políticas/permissões editoriais por papel.
- Blocos de conteúdo avançados (callout/tables/compare boxes).
- Motor de recomendação contextual por embeddings/keywords.
