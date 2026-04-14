# Guia de Postagem via JSON

Este guia mostra **como inserir uma postagem** e qual é a **estrutura completa do JSON** aceita pelo sistema.

## 1) Rotas envolvidas

- `POST /admin/posts/import-json` (auth + admin): valida e normaliza um JSON bruto para pré-preencher o formulário.
- `POST /posts` (auth + admin): cria o post.
- `PATCH /posts/{post}` (auth + admin): atualiza o post.

> Observação: para publicar direto (`status = "published"`), alguns campos tornam-se obrigatórios (ver seção de validação).

---

## 2) Estrutura completa do JSON (payload)

Use esta estrutura como referência. Todos os campos abaixo são suportados no backend.

```json
{
  "title": "O que é X e como usar",
  "slug": "o-que-e-x-e-como-usar",
  "excerpt": "Resumo curto do conteúdo para listagens e SEO.",
  "hero_title": "Título de destaque no topo",
  "hero_summary": "Resumo em destaque no hero.",
  "quick_answer": "Resposta rápida para a intenção principal do usuário.",
  "content_html": "<h2>Introdução</h2><p>Conteúdo completo em HTML...</p>",
  "featured_image": "https://seu-dominio.com/imagens/capa.jpg",
  "featured_image_alt": "Descrição da imagem de capa",
  "seo_title": "Título SEO (até 180 chars)",
  "meta_description": "Descrição SEO (até 170 chars)",
  "meta_keywords": "palavra1, palavra2, palavra3",
  "canonical_url": "https://seu-dominio.com/posts/o-que-e-x-e-como-usar",
  "schema_type": "Article",
  "search_intent": "informational",
  "content_type": "guide",
  "category_id": 1,
  "author_name": "Equipe Editorial",
  "is_published": true,
  "is_indexable": true,
  "published_at": "2026-04-14 10:30:00",
  "status": "published",
  "faq_json": [
    {
      "question": "O que é X?",
      "answer": "X é ..."
    },
    {
      "question": "Como usar X?",
      "answer": "Você pode usar X para ..."
    }
  ],
  "related_keywords": ["x significado", "como usar x", "x exemplos"],
  "tags": [1, "seo", "conteúdo"],
  "cta_title": "Quer acelerar resultados?",
  "cta_text": "Conheça nossa solução para aplicar isso em produção.",
  "cta_button_text": "Saiba mais",
  "cta_button_url": "https://seu-dominio.com/solucao"
}
```

---

## 3) Campos realmente obrigatórios

### Para **salvar rascunho**
- `title`
- `slug`
- `content_html`

### Para **publicar** (`status = "published"` ou `is_published = true`)
Além dos de rascunho, também são exigidos:
- `excerpt`
- `category_id`
- `seo_title`
- `meta_description`

Se faltar algum desses na publicação, o backend retorna erro de validação.

---

## 4) Regras importantes de formato

- `slug`: apenas formato `alpha_dash` (normalmente `texto-com-hifen`).
- `featured_image`, `canonical_url`, `cta_button_url`: devem ser URLs válidas.
- `faq_json`: array com objetos `{ "question": string, "answer": string }`.
- `related_keywords`: array de strings (até 80 chars por item).
- `tags`: aceita IDs existentes (inteiros) e/ou nomes de tag (string até 80 chars).
- `status`: apenas `draft` ou `published`.
- `search_intent`: use um destes:
  - `informational`
  - `transactional`
  - `navigational`
  - `commercial`
  - `tool-support`
  - `tutorial`
  - `glossary`

---

## 5) Como postar na prática

## Opção A — Importar JSON no admin (recomendado para revisão)

Envie o JSON como string no campo `payload_json` para:

`POST /admin/posts/import-json`

Exemplo de body (JSON HTTP):

```json
{
  "payload_json": "{\"title\":\"Meu post\",\"slug\":\"meu-post\",\"content_html\":\"<p>...</p>\"}"
}
```

A resposta retorna `data` com o payload normalizado/validado para você revisar antes de salvar.

## Opção B — Criar diretamente

Envie o payload completo diretamente para:

`POST /posts`

Exemplo (curl):

```bash
curl -X POST "https://seu-dominio.com/posts" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer SEU_TOKEN" \
  -d '{
    "title": "Meu post",
    "slug": "meu-post",
    "content_html": "<p>Conteúdo</p>",
    "status": "draft"
  }'
```

---

## 6) Checklist para “dar certo” na inserção

1. Confirmar autenticação com usuário admin.
2. Garantir `category_id` válido (existente em `post_categories`) se for publicar.
3. Enviar `content_html` com HTML válido.
4. Se publicar, enviar também: `excerpt`, `seo_title`, `meta_description`.
5. Conferir URLs válidas (`featured_image`, `canonical_url`, `cta_button_url`).
6. Validar `tags` (IDs existentes ou strings válidas).
7. Preferir fluxo de import (`/admin/posts/import-json`) para checagem antes do save final.

