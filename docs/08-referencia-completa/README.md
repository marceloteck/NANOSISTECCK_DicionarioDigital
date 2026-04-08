# 08) Referência Completa

## Rotas web

- `GET /` → home
- `GET /robots.txt` → robots dinâmico
- `GET /sitemap.xml` → sitemap dinâmico
- `GET /posts` → listagem de posts (módulo posts)
- `GET /posts/{slug}` → detalhe de post
- `POST /posts` → criar post (auth)
- `PATCH /posts/{post}` → atualizar post (auth)
- `GET /categoria/{slug}` → listagem por categoria (módulo taxonomy)
- `GET /tag/{slug}` → listagem por tag (módulo taxonomy)
- `GET /tools` → listagem de tools (módulo tools)
- `GET /tools/{slug}` → detalhe de tool
- `GET /institucional/{slug}` → páginas institucionais (módulo institutional)
- `GET /buscar?q=` → busca interna (módulo search)
- `POST /contact/send` → envio de e-mail de contato

## Rotas auxiliares

- `routes/auth.php` → autenticação Breeze
- `routes/api.php` → endpoint `/api/user` autenticado via Sanctum
- `routes/channels.php` e `routes/console.php` → padrões Laravel

## Configs-chave

- `config/project.php` → engine modular do projeto
- `config/seo.php` → SEO global
- `config/app.php`, `config/database.php`, `config/mail.php`, etc. → infraestrutura Laravel

## Views Blade

- `resources/views/app.blade.php` → entry shell da SPA Inertia
- `resources/views/seo/sitemap.blade.php` → template XML do sitemap
- `resources/views/configApp/analytics.php` → analytics em produção
- `resources/views/mails/*` → templates de e-mail

## Mapa rápido de código

- `app/Http/Controllers/` → HTTP layer
- `app/Http/Requests/` → validação
- `app/Support/` → núcleo de regras de negócio
- `app/Models/` → entidades Eloquent
- `database/migrations/` → schema
- `database/seeders/` → dados de boot
- `resources/PagesVuejs/` → páginas/componentes da UI

## Observações finais

- O projeto foi desenhado para ser **reutilizável** em múltiplos nichos.
- A forma correta de escalar é por:
  - perfil de projeto,
  - módulos,
  - camada de suporte (`app/Support`),
  - e novos componentes/páginas Inertia.

