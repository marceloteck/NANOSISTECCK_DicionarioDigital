# 07) Operação, Build, CI/CD e Deploy

## Build local

```bash
npm run build
```

Arquivos gerados em `public/build`.

## Pipelines GitHub Actions

Há dois workflows para branches específicas:
- `.github/workflows/main.yml` → branch `app_musicPro`
- `.github/workflows/Trevo4Folhas.yml` → branch `Trevo4Folhas`

Fluxo dos workflows:
1. checkout da branch alvo
2. setup PHP 8.2 e Node 18
3. `npm install`
4. `composer install --no-dev --prefer-dist`
5. `composer dump-autoload -o`
6. `npm run build`
7. commit/push de `public/build/`

## Checklist de release

1. Validar `.env` de produção
2. Rodar migrations
3. Rodar build frontend
4. Conferir `robots.txt` e `sitemap.xml`
5. Validar rotas habilitadas por módulo
6. Verificar comportamento SEO por página (title, canonical, schema)

## Comandos úteis de manutenção

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

