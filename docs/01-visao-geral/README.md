# 01) Visão Geral do Projeto

## O que esta base resolve

Esta base implementa um **engine universal** para sites com foco em SEO e performance de conteúdo. O comportamento é controlado por:
- tipo de projeto (`content`, `tools`, `hybrid`, `custom`),
- módulos habilitados/desabilitados,
- layout por tipo de página,
- regras de indexação e monetização.

## Tipos de projeto

Definidos em `config/project.php`:
- `content`: editorial + taxonomia
- `tools`: catálogo e páginas de ferramentas
- `hybrid`: mistura conteúdo e ferramentas
- `custom`: perfil livre

## Módulos disponíveis

A chave `project.modules` controla os módulos:
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

## Fluxo de alto nível

1. Requisição HTTP entra em `routes/web.php`
2. Controller resolve dados + SEO
3. Resposta renderizada com Inertia para página Vue
4. Layout e componentes recebem `seo`, `pageType`, conteúdo e módulos ativos
5. Head/meta/schema final são montados no frontend

## Estrutura macro de pastas

- `app/` → domínio da aplicação (Controllers, Models, Support)
- `config/` → configuração do Laravel + projeto + SEO
- `database/` → migrations, factories, seeders
- `resources/PagesVuejs/` → páginas e componentes Vue
- `resources/js/` → bootstrap e registro de componentes
- `resources/views/` → shell Blade do Inertia + templates de e-mail
- `routes/` → rotas web/api/auth/channels
- `tests/` → testes de feature e unit
- `docs/` → documentação funcional/técnica

