# Relatório Técnico Sênior — Diagnóstico e Direção de Evolução

## 1) Resumo executivo

Este projeto já nasce com uma base forte para escalar um site profissional:
- backend Laravel 10 organizado por domínio,
- frontend Vue 3 + Inertia,
- camadas de SEO avançadas (meta, JSON-LD, canonical, robots e sitemap),
- arquitetura modular por configuração (`project_type` + módulos),
- testes de feature para fluxo principal de conteúdo.

A fundação está boa para produto real. O que falta para chegar em nível “enterprise sem retrabalho” é:
1. padronizar o modelo de domínio e modularização para reduzir acoplamento futuro;
2. endurecer qualidade de build/CI (ambiente, versões e validações);
3. formalizar observabilidade, segurança e governança de dados/conteúdo.

---

## 2) Diagnóstico arquitetural atual

### 2.1 Backend (Laravel)

**Pontos fortes:**
- Controllers orquestram HTTP e delegam para classes de domínio em `app/Support/*`.
- Regras de projeto desacopladas em serviços (`ModuleManager`, `ProjectProfile`, `PageTypeRegistry`).
- SEO centralizado via `SeoBuilder`, `SchemaBuilder`, `SitemapBuilder`, `RobotsBuilder`.
- Regras de publicação/indexação no modelo `Post` com `scopePublished()` e `scopeIndexable()`.
- Requests dedicadas para criação/edição de post (`PostStoreRequest` / `PostUpdateRequest`).

**Riscos técnicos identificados:**
- Camada `Support` está virando “super pasta” (bom no início, mas tende a crescer sem fronteiras explícitas).
- A lógica de habilitação de módulos aparece em mais de um ponto (rotas e serviços), exigindo disciplina para não divergir.
- Alguns nomes/estrutura sugerem legado de template (ex.: `HomeContollerRoutes` com typo em “Controller”). Não quebra, mas reduz clareza/manutenibilidade.

### 2.2 Frontend (Vue + Inertia)

**Pontos fortes:**
- Entrada única com Inertia e registro de componentes globais.
- Layouts separados por contexto (`Content`, `Tools`, `Hybrid`, `Institutional`).
- Componente `AppHead.vue` bem completo para SEO em runtime.

**Riscos técnicos identificados:**
- Mistura de Bootstrap + Tailwind + SCSS aumenta custo cognitivo e risco de inconsistência visual.
- Dependências duplicadas com versões potencialmente conflitantes (`vue` e `@vitejs/plugin-vue` em `dependencies` e `devDependencies`).
- A home atual é mais “sandbox de validação técnica” do que página de produto final (correto para template, mas precisa trilha de produto).

### 2.3 SEO e crescimento orgânico

**Pontos fortes:**
- Estratégia madura: canonical, robots dinâmico, sitemap dinâmico, schema graph e fallback de mídia.
- Política de `noindex` para paginação e busca (boa prática para reduzir canibalização).
- Base pronta para conteúdo editorial e páginas transacionais (tools).

**Riscos técnicos identificados:**
- Sem rotina explícita de auditoria SEO automatizada no CI.
- Não há evidência de monitoramento de cobertura/indexação por ambiente (Search Console, alertas, health checks SEO).

### 2.4 Dados, testes e operação

**Pontos fortes:**
- Migrations para `posts`, taxonomias e `tools`.
- Testes de feature cobrindo fluxos-chave de publicação/sitemap/noindex/relacionados.

**Riscos técnicos identificados:**
- Ambiente atual de execução usa PHP `8.5.x-dev`, incompatível com versões travadas no lock (impacta build e previsibilidade).
- Sem evidência local de pipeline de qualidade completo (lint, análise estática PHP/JS, testes frontend, mutation test).
- Falta de playbook operacional com SLO/SLA, rollback e observabilidade de negócio.

---

## 3) O que está pronto para produção e o que ainda não está

### Pronto (base sólida)
- Estrutura Laravel + Inertia modular.
- SEO técnico centralizado.
- Modelagem inicial de conteúdo, categorias/tags e tools.
- Infra de rotas condicionais por configuração.

### Não pronto (para “não refazer depois”)
- Contrato de arquitetura de módulos/domínios (bounded contexts formais).
- Padrão de design system único (evitar guerra Bootstrap x Tailwind).
- Esteira CI/CD de qualidade robusta com gates obrigatórios.
- Estratégia de dados/editorial de longo prazo (versionamento, revisão, auditoria).
- Observabilidade full-stack (APM, logs estruturados, métricas de produto e SEO).

---

## 4) Direção recomendada (visão de dev sênior)

### 4.1 Norte arquitetural

Adotar três pilares:
1. **Domain-first**: cada módulo (Posts, Tools, Search, Institutional, SEO) com fronteiras e contratos explícitos.
2. **Quality-first**: nenhuma entrega sem testes, lint, análise estática e critérios de observabilidade.
3. **Operational-first**: arquitetura pensada para deploy/release/rollback desde o início.

### 4.2 Estratégia de evolução sem retrabalho

#### Fase 1 — Hardening da base (1–2 semanas)
- Congelar stack mínima estável de runtime (PHP/Laravel/Node/NPM) e documentar matriz de compatibilidade.
- Ajustar dependências JS para eliminar duplicidades e ambiguidade de versão.
- Definir padrão de código (Pint + PHPStan + ESLint + Prettier) com execução em CI obrigatória.
- Criar health checks técnicos e SEO (robots/sitemap/canonical/title/description/schema).

#### Fase 2 — Arquitetura modular real (2–4 semanas)
- Formalizar módulos como “pacotes internos” (ao menos por pastas e contratos):
  - `Domain/Posts`, `Domain/Tools`, `Domain/SEO`, `Domain/Institutional`.
- Introduzir camada de aplicação para casos de uso (ex.: `CreatePost`, `PublishPost`, `BuildSitemap`).
- Separar DTO/Resource para payload Inertia e evitar crescimento de arrays ad hoc em controllers.

#### Fase 3 — Produto, UX e escala (contínuo)
- Definir design system único (preferencialmente Tailwind + tokens, ou Bootstrap, não ambos de forma descontrolada).
- Implementar cache estratégico (listagens, SEO payloads, sitemap) com invalidação por evento.
- Adicionar observabilidade:
  - erro (Sentry/Bugsnag),
  - performance (APM),
  - métricas (Core Web Vitals, tráfego orgânico, CTR por template).

---

## 5) Blueprint recomendado para “site profissional completo”

### 5.1 Estrutura de produto

- **Camada de Conteúdo (Editorial):** posts, categorias, tags, autores, atualização recorrente.
- **Camada de Ferramentas (Transacional):** páginas utilitárias com intenção de busca alta.
- **Camada Institucional (Confiança):** sobre, contato, privacidade, termos.
- **Camada SEO/Distribuição:** schema, sitemap, robots, internal linking, related content.

### 5.2 Requisitos não-funcionais obrigatórios

- Segurança (OWASP básico, headers, validação forte, sanitização consistente).
- Performance (budget de LCP/CLS/INP, imagens otimizadas, cache + CDN).
- Observabilidade (logs com correlação, métricas por rota e módulo).
- Confiabilidade (backup, rollback, deploy canário quando aplicável).
- Governança (versionamento de conteúdo crítico e rastreabilidade de alterações).

### 5.3 Qualidade e testes

Mínimo para não retrabalhar:
- testes unitários de domínio,
- feature tests por rota crítica,
- smoke E2E (home, listagem, detalhe, busca, institucional),
- validação automatizada de SEO técnico,
- validação de schema JSON-LD e links canônicos.

---

## 6) Backlog priorizado (alto impacto)

### P0 (imediato)
- Corrigir ambiente de build para versões compatíveis com `composer.lock`.
- Consolidar dependências frontend e remover duplicações.
- Definir e habilitar gates de CI: `pint`, testes PHP, lint frontend, build de produção.
- Criar documentação de arquitetura alvo (ADRs curtos).

### P1 (curto prazo)
- Refatorar controllers para casos de uso e resources/DTO.
- Padronizar design system e reduzir mistura de frameworks CSS.
- Introduzir cache e invalidadores por evento de publicação/edição.

### P2 (médio prazo)
- Painel editorial robusto (workflow revisão/aprovação/publicação).
- Observabilidade e analytics orientados a SEO e receita.
- Planejamento de escalabilidade de dados e filas para tarefas pesadas.

---

## 7) Definição de pronto (Definition of Done) sugerida

Uma entrega só é “pronta” quando:
1. possui teste automatizado cobrindo regra principal;
2. mantém cobertura mínima acordada;
3. passa em lint/análise estática/build;
4. inclui telemetria mínima (log + métrica);
5. atualiza documentação técnica essencial;
6. valida SEO técnico da página/rota impactada.

---

## 8) Conclusão objetiva

A base do projeto é **boa e estratégica**: já existe um “motor universal” para sites de conteúdo, ferramentas e modelo híbrido. O caminho profissional para evitar retrabalho não é reescrever tudo; é **endurecer governança técnica, modularização de domínio e qualidade operacional**.

Se seguirmos o roadmap por fases acima, o projeto evolui para um padrão de engenharia sênior, com previsibilidade, escala e baixo custo de manutenção.
