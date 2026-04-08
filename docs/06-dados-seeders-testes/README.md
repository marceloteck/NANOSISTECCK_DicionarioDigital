# 06) Dados, Seeders, Factories e Testes

## Modelagem de dados

## Tabelas principais

- `users`
- `post_categories`
- `post_tags`
- `posts`
- `post_post_tag` (pivot)
- `tools`
- tabelas padrão Laravel (`failed_jobs`, `personal_access_tokens`, etc.)

## Estrutura de conteúdo

### `posts`
Campos de SEO e publicação:
- `title`, `slug`, `excerpt`, `content_html`
- `seo_title`, `meta_description`, `meta_keywords`, `canonical_url`
- `schema_type`, `search_intent`, `content_type`
- `is_published`, `is_indexable`, `published_at`, `status`
- `faq_json`, `related_keywords`

### `tools`
Campos de SEO e utilitário:
- `title`, `slug`, `excerpt`, `description`
- `seo_title`, `meta_description`, `canonical_url`
- `faq_json`, `how_to_steps`
- `is_published`, `is_indexable`

## Seeders

- `DatabaseSeeder`: chama `PostDemoSeeder` em `local/testing`
- `PostDemoSeeder`: cria categoria, tags e post de demonstração

## Factories

- `PostFactory` com estado `draft()`
- `PostCategoryFactory`
- `PostTagFactory`
- `UserFactory`

## Testes

### Feature tests relevantes
- `PostSystemTest`
  - post publicado acessível
  - draft oculto
  - sitemap só com itens públicos/indexáveis
  - saneamento HTML e slug duplicado
  - related resolver
  - paginação com `noindex`
- testes de autenticação/profile do Breeze

### Executar

```bash
php artisan test
```

