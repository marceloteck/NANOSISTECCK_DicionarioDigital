# 04) Arquitetura Frontend (Vue + Inertia + Vite)

## Entry point

A aplicação inicia em `resources/js/config/app.js`:
- importa CSS/SCSS e Bootstrap,
- cria app Inertia,
- resolve páginas via `resolvePageComponent`,
- registra Ziggy para rotas Laravel no Vue,
- registra componentes globais do mapa `components.js`.

## Shell Blade do Inertia

`resources/views/app.blade.php`:
- define base HTML,
- injeta `@vite` com entry JS + página corrente,
- aplica `@inertiaHead` para metadados dinâmicos,
- inclui assets globais e analytics em produção.

## Estrutura Vue

### Páginas
`resources/PagesVuejs/Pages/`
- `index/` (home)
- `posts/` (index/show/taxonomy)
- `tools/` (index/show)
- `search/` (busca)
- `institutional/` (institucionais)

### Layouts
`resources/PagesVuejs/components/Layouts/`
- `SiteLayout.vue`
- `ContentSiteLayout.vue`
- `ToolsSiteLayout.vue`
- `HybridSiteLayout.vue`
- `InstitutionalLayout.vue`
- `LayoutTopPages.vue`

### Componentes
`resources/PagesVuejs/components/`
- `Applications/` (loading, particles, AppHead)
- `ContentPages/` (ex.: paginação de posts)
- `Navs/` (navbar)
- placeholders de `Buttons/`, `Forms/`, `Modals/`

## Estilos

- `resources/css/` (base, tailwind, fontes, scrollbar)
- `resources/scss/` (`app.scss`, variáveis, componentes)
- `tailwind.config.js` e `postcss.config.js` ajustam pipeline CSS

## Build

- Dev: `npm run dev`
- Produção: `npm run build`
- Bundler: Vite (`vite.config.js`)

